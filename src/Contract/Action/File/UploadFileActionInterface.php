<?php

namespace OneToMany\LlmSdk\Contract\Action\File;

use OneToMany\LlmSdk\Request\File\UploadFileRequest;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

interface UploadFileActionInterface
{
    public function act(UploadFileRequest $request): UploadFileResponse;
}
