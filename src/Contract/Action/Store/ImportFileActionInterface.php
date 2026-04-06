<?php

namespace OneToMany\LlmSdk\Contract\Action\Store;

use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Response\SearchStore\ImportFileResponse;

interface ImportFileActionInterface
{
    public function act(ImportUploadedFileRequest $request): ImportFileResponse;
}
