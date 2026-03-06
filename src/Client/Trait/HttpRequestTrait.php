<?php

namespace OneToMany\LlmSdk\Client\Trait;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

use function array_merge_recursive;

trait HttpRequestTrait
{
    use DenormalizeTrait;

    /**
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        $options = array_merge_recursive($options, $this->buildAuthOptions());

        try {
            $response = $this->httpClient->request($method, $url, $options);

            /** @var int<100, 599> $statusCode */
            $statusCode = $response->getStatusCode();

            /** @var array<mixed> $content */
            $content = $response->toArray(false);

            if ($statusCode >= 300 || $this->hasBodyError($content)) {
                throw new RuntimeException($this->extractErrorMessage($content), $statusCode);
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $content;
    }

    /**
     * @param array<mixed> $content
     */
    protected function hasBodyError(array $content): bool
    {
        return isset($content['error']);
    }

    /**
     * @return array<mixed>
     */
    abstract protected function buildAuthOptions(): array;

    /**
     * @param array<mixed> $content
     */
    abstract protected function extractErrorMessage(array $content): string;
}
