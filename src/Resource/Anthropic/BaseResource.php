<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Resource\AbstractResource;
use OneToMany\LlmSdk\Resource\Anthropic\Type\Error\Error;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;

use function implode;
use function ltrim;
use function sprintf;

abstract readonly class BaseResource extends AbstractResource
{
    protected function doRequest(string $method, string $url, array $options = []): string
    {
        return parent::doRequest($method, $this->generateUrl($url), $options + [
            'headers' => [
                'x-api-key' => $this->apiKey,
                'anthropic-version' => $this->apiVersion,
                'anthropic-beta' => 'files-api-2025-04-14',
            ],
        ]);
    }

    // /**
    //  * @see OneToMany\LlmSdk\Resource\Trait\HttpRequestTrait
    //  *
    //  * @return array<mixed>
    //  */
    // protected function buildAuthOptions(): array
    // {
    //     return [
    //         'headers' => [
    //             'x-api-key' => $this->apiKey,
    //             'anthropic-version' => $this->apiVersion,
    //         ],
    //     ];
    // }

    /**
     * @see OneToMany\LlmSdk\Resource\Trait\HttpRequestTrait
     *
     * @param array<mixed> $content
     */
    protected function extractErrorMessage(array $content): string
    {
        return 'bad request';
        // $error = $this->parseResponse($content, Error::class, [
        //     UnwrappingDenormalizer::UNWRAP_PATH => '[error]',
        // ]);

        // return $error->message;
    }

    /**
     * @return non-empty-string
     */
    protected function generateUrl(string ...$paths): string
    {
        return sprintf('https://api.anthropic.com/v1/%s', ltrim(implode('/', $paths), '/'));
    }
}
