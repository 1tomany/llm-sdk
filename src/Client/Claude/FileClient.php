<?php

namespace OneToMany\LlmSdk\Client\Claude;

use OneToMany\LlmSdk\Client\Claude\Type\File\DeletedFile;
use OneToMany\LlmSdk\Client\Claude\Type\File\File;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

use function array_merge_recursive;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = $this->generateUrl('files');

        $content = $this->doRequest('POST', $url, [
            'body' => [
                'file' => $request->openFileHandle(),
            ],
        ]);

        $file = $this->denormalize($content, File::class);

        return new UploadResponse($request->getModel(), $file->id, $file->filename);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $content = $this->doRequest('DELETE', $this->generateUrl('files', $request->getUri()));

        $deletedFile = $this->denormalize($content, DeletedFile::class);

        return new DeleteResponse($request->getModel(), $deletedFile->id);
    }

    /**
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        $options = array_merge_recursive($options, [
            'headers' => [
                'anthropic-beta' => 'files-api-2025-04-14',
            ],
        ]);

        return parent::doRequest($method, $url, $options);
    }
}
