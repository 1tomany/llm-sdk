<?php

namespace OneToMany\AI\Contract\Action\File;

use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\UploadResponse;

interface UploadFileActionInterface
{
    public function act(UploadRequest $request): UploadResponse;
}
