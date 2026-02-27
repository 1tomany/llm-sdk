<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\OpenAI\Type\File\DeletedFile;
use OneToMany\LlmSdk\Client\OpenAI\Type\File\Enum\Purpose;
use OneToMany\LlmSdk\Client\OpenAI\Type\File\File;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class FileClient extends BaseClient implements FileClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\FileClientInterface
     */
    public function upload(UploadRequest $request): UploadResponse
    {
        $url = $this->generateUrl('files');

        $purpose = Purpose::create($request->getPurpose());

        $content = $this->doRequest('POST', $url, [
            'body' => [
                'purpose' => $purpose->getValue(),
                'file' => $request->openFileHandle(),
            ],
        ]);

        $file = $this->denormalize($content, File::class);

        return new UploadResponse($request->getModel(), $file->id, $file->filename, $file->purpose->getValue(), $file->getExpiresAt());
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
}
