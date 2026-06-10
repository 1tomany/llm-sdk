<?php

namespace OneToMany\LlmSdk\Client\Gemini\Request\File;

use OneToMany\LlmSdk\Client\Gemini\Response\File\UploadFileResponse;
use OneToMany\LlmSdk\Contract\Client\Enum\HttpMethod;
use OneToMany\LlmSdk\Contract\Client\Request\ClientRequestInterface;
use OneToMany\LlmSdk\Transfer\Input\File\UploadFileInput;

/**
 * @implements ClientRequestInterface<UploadFileResponse>
 */
final class UploadFileRequest implements ClientRequestInterface
{
    public const string COMMAND_HEADER = 'start';
    public const string PROTOCOL_HEADER = 'resumable';

    /**
     * @param non-empty-string $name
     * @param non-negative-int $size
     * @param non-empty-string $format
     */
    public function __construct(
        public readonly string $name,
        public readonly int $size,
        public readonly string $format,
    ) {
    }

    public static function create(UploadFileInput $uploadFileInput): static
    {
        return new static($uploadFileInput->name, $uploadFileInput->size, $uploadFileInput->format);
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
        get => 'upload/v1beta/files';
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
    public function getHeaders(): array
    {
        return [
            'x-goog-upload-command' => self::COMMAND_HEADER,
            'x-goog-upload-protocol' => self::PROTOCOL_HEADER,
            'x-goog-upload-header-content-length' => $this->size,
            'x-goog-upload-header-content-type' => $this->format,
        ];
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
