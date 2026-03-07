<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Resource\Gemini\Type\Error\Error;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\AbstractResource;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function array_merge_recursive;
use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource extends AbstractResource
{
    /**
     * @param array<string, int|string|null> $headers
     *
     * @return array<string, int|string|null>
     */
    protected function buildHttpHeaders(array $headers = []): array
    {
        /**
         * @var array<string, int|string|null> $headers
         */
        $headers = array_merge_recursive($headers, [
            'x-goog-api-key' => $this->getApiKey(),
        ]);

        return $headers;
    }

    /**
     * @see OneToMany\LlmSdk\Resource\AbstractResource
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
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://generativelanguage.googleapis.com/%s', ltrim(implode('/', $paths), '/'));
    }

    /**
     * @return non-empty-string
     */
    protected function generateModelUrl(string $model, string $action): string
    {
        return $this->generateUrl($this->getApiVersion(), 'models', sprintf('%s:%s', $model, $action));
    }
}
