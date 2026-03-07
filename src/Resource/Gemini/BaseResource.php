<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\AbstractResource;
use OneToMany\LlmSdk\Resource\Gemini\Type\Error\Error;
use OneToMany\LlmSdk\Resource\Trait\TransportTrait;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function sprintf;

abstract readonly class BaseResource extends AbstractResource
{
    use TransportTrait;

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    public function getBaseUrl(): string
    {
        return 'https://generativelanguage.googleapis.com';
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    protected function getStandardHeaders(): array
    {
        return ['x-goog-api-key' => $this->apiKey];
    }

    /**
     * @param array<string, int|string|null> $headers
     *
     * @return array<string, int|string|null>
     */
    protected function buildHttpHeaders(array $headers = []): array
    {
        return $headers;
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    protected function handleHttpError(string $content, int $statusCode): never
    {
        $error = $this->doDeserialize($content, Error::class, context: [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        throw new RuntimeException($error->getMessage(), $statusCode);
    }

    /**
     * @return non-empty-string
     */
    protected function buildModelUrl(string $model, string $action): string
    {
        return $this->buildUrl($this->apiVersion, 'models', sprintf('%s:%s', $model, $action));
    }
}
