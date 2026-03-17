<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\File\DeleteFileRequest;
use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

interface FilesResourceInterface
{
    public function upload(UploadFileRequest $request): UploadFileResponse;

    public function delete(DeleteFileRequest $request): DeleteFileResponse;
}
