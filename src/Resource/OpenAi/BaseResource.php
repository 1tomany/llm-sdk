<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\AbstractResource;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Error\Error;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource extends AbstractResource
{
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
        return sprintf('https://api.openai.com/%s/%s', $this->getApiVersion(), ltrim(implode('/', $paths), '/'));
    }
}
