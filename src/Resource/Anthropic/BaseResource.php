<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Client\Trait\HttpRequestTrait;
use OneToMany\LlmSdk\Resource\Anthropic\Type\Error\Error;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource
{
    use HttpRequestTrait;

    public function __construct(
        protected DenormalizerInterface $denormalizer,
        protected HttpClientInterface $httpClient,
        protected string $apiKey,
        protected string $apiVersion,
    ) {
    }

    /**
     * @return array<mixed>
     */
    protected function buildAuthOptions(): array
    {
        return [
            'headers' => [
                'x-api-key' => $this->apiKey,
                'anthropic-version' => $this->apiVersion,
            ],
        ];
    }

    /**
     * @param array<mixed> $content
     */
    protected function extractErrorMessage(array $content, int $statusCode): string
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
        return sprintf('https://api.anthropic.com/v1/%s', ltrim(implode('/', $paths), '/'));
    }
}
