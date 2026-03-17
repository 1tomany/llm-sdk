<?php

namespace OneToMany\LlmSdk\Contract\Action\File;

use OneToMany\LlmSdk\Request\File\DeleteFileRequest;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;

interface DeleteFileActionInterface
{
    public function act(DeleteFileRequest $request): DeleteFileResponse;
}
