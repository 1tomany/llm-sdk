<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Exception\RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource
{
    // use DenormalizeTrait;

    public function __construct(
        // protected DenormalizerInterface $denormalizer,
        protected HttpClientInterface $httpClient,
        #[\SensitiveParameter] protected string $apiKey,
    ) {
    }

    protected function doRequest(
        string $method,
        string $url,
        ?array $json = null,
        ?array $body = null,
    ): ResponseInterface
    {
        $url = $this->generateUrl($url);

        $options = ['json' => $json, 'body' => $body];

        try {
            $response = $this->httpClient->request($method, $url, $options + [
                'auth_bearer' => $this->apiKey,
            ]);

            /** @var int<100, 599> $statusCode */
            $statusCode = $response->getStatusCode();

            $headers = $response->getHeaders(false);

            if (isset($headers['content-type'][0]) && 0 === \stripos($headers['content-type'][0], 'application/json')) {

            }

            // print_r($headers);

            if (200 !== $statusCode) {
                throw new RuntimeException('Request failed!', $statusCode);
            }
        } catch (HttpClientExceptionInterface $e) {
            throw new RuntimeException($e->getMessage(), previous: $e);
        }

        return $response;
    }

    protected function doPostRequest(
        string $url,
        ?array $json = null,
        ?array $body = null,
    ): ResponseInterface
    {
        return $this->doRequest('POST', $url, $json, $body);
    }

    /**
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://api.openai.com/v1/%s', ltrim(implode('/', $paths), '/'));
    }
}
