<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\Error\Error;
use OneToMany\LlmSdk\Resource\AbstractResource;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource extends AbstractResource
{
    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpRequestTrait
     *
     * @return array<mixed>
     */
    protected function buildAuthOptions(): array
    {
        return [
            'headers' => [
                'x-goog-api-key' => $this->apiKey,
            ],
        ];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpRequestTrait
     *
     * @param array<mixed> $content
     */
    protected function extractErrorMessage(array $content): string
    {
        return 'another bad thing happened';
        // $error = $this->parseResponse($content, Error::class, [
        //     UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        // ]);

        // return $error->getMessage();
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
        return $this->generateUrl($this->apiVersion, 'models', sprintf('%s:%s', $model, $action));
    }
}
