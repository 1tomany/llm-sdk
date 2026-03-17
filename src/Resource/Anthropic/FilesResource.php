<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteFileRequest;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Resource\Anthropic\Type\Response\File\DeletedFile;
use OneToMany\LlmSdk\Resource\Anthropic\Type\Response\File\File;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadFileRequest $request): UploadFileResponse
    {
        $url = $this->buildUrl('files');

        $response = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders([
                'anthropic-beta' => $this->filesVersion,
            ]),
            'body' => [
                'file' => $request->openFile(),
            ],
        ]);

        $object = $this->doDenormalize($response, File::class);

        return new UploadFileResponse($request->getVendor(), $object->id, $object->filename);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteFileRequest $request): DeleteFileResponse
    {
        $url = $this->buildUrl('files', $request->getUri());

        $response = $this->doDeleteRequest($url, [
            'headers' => $this->buildHeaders([
                'anthropic-beta' => $this->filesVersion,
            ]),
        ]);

        $object = $this->doDenormalize($response, DeletedFile::class);

        return new DeleteFileResponse($request->getVendor(), $object->id);
    }
}
