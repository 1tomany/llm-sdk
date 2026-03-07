<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\AbstractResource;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Error\Error;
use OneToMany\LlmSdk\Resource\Trait\TransportTrait;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

abstract readonly class BaseResource extends AbstractResource
{
    use TransportTrait;

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    public function getBaseUrl(): string
    {
        return 'https://api.openai.com/v1';
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    protected function getBaseHeaders(): array
    {
        return [];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\TransportTrait
     */
    protected function handleRequestError(string $content, int $statusCode): never
    {
        $error = $this->doDeserialize($content, Error::class, context: [
            UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        ]);

        throw new RuntimeException($error->message, $statusCode);
    }
}
