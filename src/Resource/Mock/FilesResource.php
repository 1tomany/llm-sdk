<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

final readonly class FilesResource implements FilesResourceInterface
{
    use GenerateIdTrait;

    public function __construct()
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadFileResponse
    {
        return new UploadFileResponse($request->getVendor(), $this->generateId('file'));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteFileResponse
    {
        return new DeleteFileResponse($request->getVendor(), $request->getUri());
    }
}
