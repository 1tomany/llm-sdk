<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Resource\Gemini\Type\Error\Error;
use OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function sprintf;

abstract readonly class BaseResource
{
    use HttpResourceTrait;

    /**
     * @param non-empty-string $apiKey
     * @param non-empty-string $apiVersion
     */
    public function __construct(
        protected HttpClientInterface $httpClient,
        protected DenormalizerInterface&NormalizerInterface&SerializerInterface $serializer,
        protected string $apiKey,
        protected string $apiVersion,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    public function getBaseUrl(): string
    {
        return 'https://generativelanguage.googleapis.com';
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    protected function getBaseHeaders(): array
    {
        return ['x-goog-api-key' => $this->apiKey];
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpResourceTrait
     */
    protected function handleRequestError(string $content, int $statusCode): never
    {
        try {
            $error = $this->doDeserialize($content, Error::class, context: [
                UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
            ]);
        } catch (ExceptionInterface) {
            $error = new Error($statusCode, $content);
        }

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
