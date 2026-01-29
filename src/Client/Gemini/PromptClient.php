<?php

namespace App\Prompt\Vendor\Model\Client\Gemini;

use App\Prompt\Vendor\Model\Client\Exception\ConnectingToHostFailedException;
use App\Prompt\Vendor\Model\Client\Exception\DecodingResponseContentFailedException;
use App\Prompt\Vendor\Model\Client\Gemini\Type\Content\GenerateContentResponse;
use App\Prompt\Vendor\Model\Client\Gemini\Type\Error\Status;
use App\Prompt\Vendor\Model\Contract\Client\PromptClientInterface;
use App\Prompt\Vendor\Model\Contract\Request\Prompt\SendPromptRequestInterface;
use App\Prompt\Vendor\Model\Contract\Response\Prompt\SentPromptResponseInterface;
use App\Prompt\Vendor\Model\Exception\RuntimeException;
use App\Prompt\Vendor\Model\Response\Prompt\SentPromptResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface as HttpClientDecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface as HttpClientTransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function sprintf;

final readonly class PromptClient implements PromptClientInterface
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private DenormalizerInterface $denormalizer,
    ) {
    }

    public function send(SendPromptRequestInterface $request): SentPromptResponseInterface
    {
        $timer = new Stopwatch(true)->start('send');

        try {
            $url = $this->generateUrl($request->model);

            // Generate a signed URL to upload the file to
            $response = $this->httpClient->request('POST', $url, [
                'json' => $request->request,
            ]);

            /** @var array<string, mixed> $responseContent */
            $responseContent = $response->toArray(false);

            if (200 !== $response->getStatusCode()) {
                $status = $this->denormalizer->denormalize($responseContent, Status::class, null, [
                    UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
                ]);

                throw new RuntimeException($status->message, $status->code);
            }

            $generateContentResponse = $this->denormalizer->denormalize($responseContent, GenerateContentResponse::class);
        } catch (HttpClientTransportExceptionInterface $e) {
            throw new ConnectingToHostFailedException($url, $e);
        } catch (HttpClientDecodingExceptionInterface|SerializerExceptionInterface $e) {
            throw new DecodingResponseContentFailedException('Sending the prompt', $e);
        }

        return new SentPromptResponse($request->model, $generateContentResponse->responseId, $generateContentResponse->getOutput(), $responseContent, $timer->stop()->getDuration());
    }

    /**
     * @param non-empty-lowercase-string $model
     *
     * @return non-empty-string
     */
    private function generateUrl(string $model): string
    {
        return sprintf('https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent', $model);
    }
}
