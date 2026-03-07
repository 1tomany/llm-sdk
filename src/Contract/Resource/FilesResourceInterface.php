<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;
use OneToMany\LlmSdk\Response\File\UploadResponse;

interface FilesResourceInterface
{
    public function upload(UploadRequest $request): UploadResponse;

    public function delete(DeleteRequest $request): DeleteResponse;
}
