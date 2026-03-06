<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\Anthropic\Type\File\DeletedFile;
use OneToMany\LlmSdk\Resource\Anthropic\Type\File\File;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

use function array_merge;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = $this->generateUrl('files');

        $content = $this->doHttpPostRequest($url, [
            'headers' => $this->createHttpHeaders(),
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
        $url = $this->generateUrl('files', $request->getUri());

        $content = $this->doHttpDeleteRequest($url, [
            'headers' => $this->createHttpHeaders(),
        ]);

        $file = $this->doDeserialize($content, DeletedFile::class);

        return new DeleteResponse($request->getModel(), $file->id);
    }

    /**
     * @see OneToMany\LlmSdk\Resource\Anthropic\BaseResource
     */
    protected function createHttpHeaders(): array
    {
        return array_merge(parent::createHttpHeaders(), [
            'anthropic-beta' => 'files-api-2025-04-14',
        ]);
    }
}
