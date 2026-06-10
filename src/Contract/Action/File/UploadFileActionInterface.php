<?php

namespace OneToMany\LlmSdk\Contract\Action\File;

use OneToMany\LlmSdk\Transfer\Input\File\UploadFileInput;
use OneToMany\LlmSdk\Transfer\Output\File\FileOutput;

interface UploadFileActionInterface
{
    public function act(UploadFileInput $input): FileOutput;
}
