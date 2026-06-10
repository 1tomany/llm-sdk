<?php

namespace OneToMany\LlmSdk\Contract\Client;

use OneToMany\LlmSdk\Transfer\Input\File\UploadFileInput;
use OneToMany\LlmSdk\Transfer\Output\File\FileOutput;

interface FileClientInterface extends ClientInterface
{
    public function uploadFile(UploadFileInput $uploadFileInput): FileOutput;

    // public function deleteFile(DeleteFileInput $deleteFileInput): FileOutput;
}
