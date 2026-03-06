<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\AbstractResource;
use OneToMany\LlmSdk\Resource\Anthropic\Type\Error\Error;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource extends AbstractResource
{
    /**
     * @return array<string>
     */
    protected function createHttpHeaders(): array
    {
        return [
            'x-api-key' => $this->getApiKey(),
            'anthropic-version' => $this->getApiVersion(),
        ];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\AbstractResource
     */
    protected function handleHttpError(string $content, int $statusCode): never
    {
        $error = $this->doDeserialize($content, Error::class, context: [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        throw new RuntimeException($error->message, $statusCode);
    }

    /**
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://api.anthropic.com/v1/%s', ltrim(implode('/', $paths), '/'));
    }
}
