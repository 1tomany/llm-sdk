<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\DeletedFile;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\Enum\Purpose;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\File\File;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

use function array_merge;

final readonly class FilesResource extends BaseResource implements FilesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function upload(UploadRequest $request): UploadFileResponse
    {
        $requestContent = [
            'file' => $request->openFile(),
        ];

        if ($purpose = $request->getPurpose()) {
            $purpose = Purpose::tryFrom($purpose);
        }

        if (!$purpose instanceof Purpose) {
            $purpose = Purpose::UserData;
        }

        $requestContent = array_merge($requestContent, [
            'purpose' => $purpose->getValue(),
        ]);

        $content = $this->doPostRequest($this->buildUrl('files'), [
            'auth_bearer' => $this->getApiKey(),
            'body' => [
                ...$requestContent,
            ],
        ]);

        $file = $this->doDenormalize($content, File::class);

        return new UploadFileResponse($request->getVendor(), $file->id, $file->filename, $file->purpose->getValue(), $file->getExpiresAt());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\FilesResourceInterface
     */
    public function delete(DeleteRequest $request): DeleteFileResponse
    {
        $url = $this->buildUrl('files', $request->getUri());

        $content = $this->doDeleteRequest($url, [
            'auth_bearer' => $this->getApiKey(),
        ]);

        $file = $this->doDenormalize($content, DeletedFile::class);

        return new DeleteFileResponse($request->getVendor(), $file->id);
    }
}
