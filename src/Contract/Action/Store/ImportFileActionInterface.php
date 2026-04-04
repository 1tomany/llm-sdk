<?php

namespace OneToMany\LlmSdk\Contract\Action\Store;

use OneToMany\LlmSdk\Request\Store\ImportFileRequest;
use OneToMany\LlmSdk\Response\Store\ImportFileResponse;

interface ImportFileActionInterface
{
    public function act(ImportFileRequest $request): ImportFileResponse;
}
