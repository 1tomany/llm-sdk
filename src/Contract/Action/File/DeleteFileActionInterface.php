<?php

namespace OneToMany\LlmSdk\Contract\Action\File;

use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;

interface DeleteFileActionInterface
{
    public function act(DeleteRequest $request): DeleteFileResponse;
}
