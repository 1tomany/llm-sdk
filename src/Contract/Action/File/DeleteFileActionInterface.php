<?php

namespace OneToMany\AI\Contract\Action\File;

use OneToMany\AI\Request\File\DeleteRequest;
use OneToMany\AI\Response\File\DeleteResponse;

interface DeleteFileActionInterface
{
    public function act(DeleteRequest $request): DeleteResponse;
}
