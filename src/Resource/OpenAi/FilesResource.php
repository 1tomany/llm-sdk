<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Resource\OpenAi\Type\File\Enum\Purpose;
use OneToMany\LlmSdk\Resource\OpenAi\Type\File\File;
use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\File\DeletedFile;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $purpose = Purpose::create($request->getPurpose());

        $content = $this->doHttpPostRequest($this->generateUrl('files'), [
            'auth_bearer' => $this->getApiKey(),
            'body' => [
                'file' => $request->openFile(),
                'purpose' => $purpose->getValue(),
            ],
        ]);

        $file = $this->doDeserialize($content, File::class);

        return new UploadResponse($request->getModel(), $file->id, $file->filename, $file->purpose->getValue(), $file->getExpiresAt());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteResponse
    {
        $url = $this->generateUrl('files', $request->getUri());

        $content = $this->doHttpDeleteRequest($url, [
            'auth_bearer' => $this->getApiKey(),
        ]);

        $file = $this->doDeserialize($content, DeletedFile::class);

        return new DeleteResponse($request->getModel(), $file->id);
    }
}
