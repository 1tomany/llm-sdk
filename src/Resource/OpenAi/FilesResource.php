<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteFileRequest;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Request\File\UploadFile;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\DeletedFile;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\File;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadFileRequest $request): UploadFileResponse
    {
        $uploadFile = new UploadFile($request->getPath(), $request->getPurpose());

        $content = $this->doPostRequest($this->buildUrl('files'), [
            'auth_bearer' => $this->getApiKey(),
            'body' => [
                ...$uploadFile->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($content, File::class);

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

        $object = $this->doDenormalize($content, DeletedFile::class);

        return new DeleteFileResponse($request->getVendor(), $object->id);
    }
}
