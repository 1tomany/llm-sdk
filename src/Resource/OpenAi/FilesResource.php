<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteFileRequest;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Request\File\File as FileRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\DeletedFile as DeletedFileResponse;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\File as FileResponse;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadFileRequest $request): UploadFileResponse
    {
        $fileRequest = new FileRequest($request->getPath(), $request->getPurpose());

        $content = $this->doPostRequest($this->buildUrl('files'), [
            'auth_bearer' => $this->getApiKey(),
            'body' => [
                ...$fileRequest->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($content, FileResponse::class);

        return new UploadFileResponse($request->getVendor(), $object->id, $object->filename, $object->purpose->getValue(), $object->getExpiresAt());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteFileRequest $request): DeleteFileResponse
    {
        $url = $this->buildUrl('files', $request->getUri());

        $content = $this->doDeleteRequest($url, [
            'auth_bearer' => $this->getApiKey(),
        ]);

        $object = $this->doDenormalize($content, DeletedFileResponse::class);

        return new DeleteFileResponse($request->getVendor(), $object->id);
    }
}
