<?php

namespace App\Prompt\Vendor\Model\Client\OpenAi;

use App\Prompt\Vendor\Model\Client\Exception\ConnectingToHostFailedException;
use App\Prompt\Vendor\Model\Client\Exception\DecodingResponseContentFailedException;
use App\Prompt\Vendor\Model\Client\OpenAi\Type\Error\Error;
use App\Prompt\Vendor\Model\Client\OpenAi\Type\File\File;
use App\Prompt\Vendor\Model\Contract\Client\FileClientInterface;
use App\Prompt\Vendor\Model\Contract\Request\File\CacheFileRequestInterface;
use App\Prompt\Vendor\Model\Contract\Response\File\CachedFileResponseInterface;
use App\Prompt\Vendor\Model\Exception\RuntimeException;
use App\Prompt\Vendor\Model\Response\File\CachedFileResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface as HttpClientTransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function sprintf;
use function stream_context_set_option;

final readonly class FileClient implements FileClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function cache(CacheFileRequestInterface $request): CachedFileResponseInterface
    {
        $fileHandle = $request->open();

        // This avoids Symfony using a MimeTypeGuesser since we already know the file format
        if (!stream_context_set_option($fileHandle, 'http', 'content_type', $request->format)) {
            throw new RuntimeException(sprintf('Setting the content type to "%s" for the file "%s" failed.', $request->format, $request->name));
        }

        $url = $this->generateUrl('files');

        try {
            $response = $this->httpClient->request('POST', $url, [
                'body' => [
                    'file' => $fileHandle,
                    'purpose' => $request->purpose,
                ],
            ]);

            $responseContent = $response->toArray(false);

            if (200 !== $response->getStatusCode() || isset($responseContent['error'])) {
                $error = $this->denormalizer->denormalize($responseContent, Error::class, null, [
                    UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
                ]);

                throw new RuntimeException($error->message);
            }

            $file = $this->denormalizer->denormalize($responseContent, File::class);
        } catch (HttpClientTransportExceptionInterface $e) {
            throw new ConnectingToHostFailedException($url, $e);
        } catch (HttpClientDecodingExceptionInterface|SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException(sprintf('Caching the file "%s"', $request->name), $e);
        }

        return new CachedFileResponse($file->id, $file->filename, $file->purpose, $file->getExpiresAt());
    }

    /**
     * @param non-empty-string $path
     *
     * @return non-empty-string
     */
    private function generateUrl(string $path): string
    {
        return sprintf('https://api.openai.com/v1/%s', $path);
    }
}
