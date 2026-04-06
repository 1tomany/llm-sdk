<?php

namespace OneToMany\LlmSdk\Contract\Action\Store;

use OneToMany\LlmSdk\Request\SearchStore\ImportSearchStoreFileRequest;
use OneToMany\LlmSdk\Response\Store\ImportFileResponse;

interface ImportFileActionInterface
{
    public function act(ImportSearchStoreFileRequest $request): ImportFileResponse;
}
