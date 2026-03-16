<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Request\File\File as FileRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\DeletedFile;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\File as FileResponse;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadFileResponse
    {
        $object = new FileRequest(
            $request->getPath(),
            $request->getPurpose(),
        );

        $content = $this->doPostRequest($this->buildUrl('files'), [
            'auth_bearer' => $this->getApiKey(),
            'body' => [
                ...$object->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($content, FileResponse::class);

        return new UploadFileResponse($request->getVendor(), $object->id, $object->filename, $object->purpose->getValue(), $object->getExpiresAt());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteFileResponse
    {
        $url = $this->buildUrl('files', $request->getUri());

        $content = $this->doDeleteRequest($url, [
            'auth_bearer' => $this->getApiKey(),
        ]);

        $file = $this->doDenormalize($content, DeletedFile::class);

        return new DeleteFileResponse($request->getVendor(), $file->id);
    }
}
