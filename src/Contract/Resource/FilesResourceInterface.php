<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

interface FilesResourceInterface
{
    public function upload(UploadRequest $request): UploadFileResponse;

    public function delete(DeleteRequest $request): DeleteFileResponse;
}
