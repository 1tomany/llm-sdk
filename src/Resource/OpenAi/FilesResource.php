<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Client\OpenAi\Type\File\Enum\Purpose;
use OneToMany\LlmSdk\Client\OpenAi\Type\File\File;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class FilesResource extends BaseClient implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $purpose = Purpose::create($request->getPurpose());

        $content = $this->doRequest('POST', $this->generateUrl('files'), [
            'body' => [
                'purpose' => $purpose->getValue(),
                'file' => $request->openFileHandle(),
            ],
        ]);

        $file = $this->denormalize($content, File::class);

        return new UploadResponse($request->getModel(), $file->id, $file->filename, $file->purpose->getValue(), $file->getExpiresAt());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $this->doRequest('DELETE', $this->generateUrl('files', $request->getUri()));

        return new DeleteResponse($request->getModel(), $request->getUri());
    }
}
