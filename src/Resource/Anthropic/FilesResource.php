<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\Anthropic\Type\File\DeletedFile;
use OneToMany\LlmSdk\Resource\Anthropic\Type\File\File;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadFileResponse
    {
        $url = $this->buildUrl('files');

        $content = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders([
                'anthropic-beta' => $this->filesVersion,
            ]),
            'body' => [
                'file' => $request->openFile(),
            ],
        ]);

        $file = $this->doDenormalize($content, File::class);

        return new UploadFileResponse($request->getVendor(), $file->id, $file->filename);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteFileResponse
    {
        $url = $this->buildUrl('files', $request->getUri());

        $content = $this->doDeleteRequest($url, [
            'headers' => $this->buildHeaders([
                'anthropic-beta' => $this->filesVersion,
            ]),
        ]);

        $file = $this->doDenormalize($content, DeletedFile::class);

        return new DeleteFileResponse($request->getVendor(), $file->id);
    }
}
