<?php

namespace OneToMany\AI\Client\Mock;

use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Request\File\DeleteRequest;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\DeleteResponse;
use OneToMany\AI\Response\File\UploadResponse;

final readonly class FileClient extends MockClient implements FileClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        return new UploadResponse($request->getModel(), $this->generateResponseId('file'));
    }

    /**
     * @see OneToMany\AI\Contract\Client\FileClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        return new DeleteResponse($request->getModel(), $request->getUri());
    }
}
