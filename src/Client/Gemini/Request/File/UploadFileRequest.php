<?php

namespace OneToMany\LlmSdk\Client\Gemini\Request\File;

use OneToMany\LlmSdk\Client\Gemini\Response\File\UploadFileResponse;
use OneToMany\LlmSdk\Contract\Client\Enum\HttpMethod;
use OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface;

/**
 * @implements ClientRequestInterface<UploadFileResponse>
 */
final class UploadFileRequest implements ClientRequestInterface
{
    /**
     * @param non-empty-string $name
     */
    public function __construct(
        public readonly string $name,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface
     */
    public HttpMethod $method {
        get => HttpMethod::POST;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface
     */
    public string $url {
        get => '/upload/v1beta/files';
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface
     */
    public string $responseType {
        get => UploadFileResponse::class;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface
     */
    public function getBody(): null
    {
        return null;
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface
     *
     * @return array{
     *   file: array{
     *     displayName: non-empty-string,
     *   },
     * }
     */
    public function getJson(): array
    {
        return [
            'file' => [
                'displayName' => $this->name,
            ],
        ];
    }
}
