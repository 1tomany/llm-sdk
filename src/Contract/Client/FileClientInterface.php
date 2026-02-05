<?php

namespace OneToMany\AI\Contract\Client;

use OneToMany\AI\Request\File\DeleteRequest;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\DeleteResponse;
use OneToMany\AI\Response\File\UploadResponse;

interface FileClientInterface extends ClientInterface
{
    public function upload(UploadRequest $request): UploadResponse;

    public function delete(DeleteRequest $request): DeleteResponse;
}
