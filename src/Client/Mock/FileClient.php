<?php

namespace OneToMany\LlmSdk\Client\Mock;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class FileClient extends BaseClient implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        return new UploadResponse($request->getModel(), $this->generateResponseId('file'));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        return new DeleteResponse($request->getModel(), $request->getUri());
    }
}
