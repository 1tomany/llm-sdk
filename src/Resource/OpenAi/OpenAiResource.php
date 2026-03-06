<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Client\OpenAi\Type\Error\Error;
use OneToMany\LlmSdk\Resource\AbstractResource;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class OpenAiResource extends AbstractResource
{
    /**
     * @param array<string, mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        return parent::doRequest($method, $url, $options + ['auth_bearer' => $this->apiKey]);
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpRequestTrait
     *
     * @param array<mixed> $content
     */
    protected function extractErrorMessage(array $content): string
    {
        $error = $this->denormalize($content, Error::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        return $error->message;
    }









    /**
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://api.openai.com/%s/%s', $this->apiVersion, ltrim(implode('/', $paths), '/'));
    }
}
