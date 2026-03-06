<?php

namespace OneToMany\LlmSdk\Resource\Anthropic;

use OneToMany\LlmSdk\Client\Anthropic\Type\File\File;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $content = $this->doRequest('POST', $this->generateUrl('files'), [
            'body' => ['file' => $request->openFileHandle()],
        ]);

        $file = $this->denormalize($content, File::class);

        return new UploadResponse($request->getModel(), $file->id, $file->filename);
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $this->doRequest('DELETE', $this->generateUrl('files', $request->getUri()));

        return new DeleteResponse($request->getModel(), $request->getUri());
    }

    /**
     * @param array<mixed> $options
     *
     * @return array<mixed>
     */
    protected function doRequest(string $method, string $url, array $options = []): array
    {
        return parent::doRequest($method, $url, $options + [
            'headers' => ['anthropic-beta' => 'files-api-2025-04-14'],
        ]);
    }
}
