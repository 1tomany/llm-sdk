<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\Anthropic\Type\File\DeletedFile;
use OneToMany\LlmSdk\Resource\Anthropic\Type\File\File;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadResponse
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

        $file = $this->doDeserialize($content, File::class);

        return new UploadResponse($request->getModel(), $file->id, $file->filename);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $url = $this->buildUrl('files', $request->getUri());

        $content = $this->doDeleteRequest($url, [
            'headers' => $this->buildHeaders([
                'anthropic-beta' => $this->filesVersion,
            ]),
        ]);

        $file = $this->doDeserialize($content, DeletedFile::class);

        return new DeleteResponse($request->getModel(), $file->id);
    }
}
