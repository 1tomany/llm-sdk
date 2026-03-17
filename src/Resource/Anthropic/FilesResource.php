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
    public function delete(DeleteFileRequest $request): DeleteFileResponse
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
