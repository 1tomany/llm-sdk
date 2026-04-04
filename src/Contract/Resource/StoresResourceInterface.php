<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;
use OneToMany\LlmSdk\Request\Store\ImportFileRequest;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;
use OneToMany\LlmSdk\Response\Store\ImportFileResponse;

interface StoresResourceInterface
{
    public function create(CreateStoreRequest $request): CreateStoreResponse;

    public function importFile(ImportFileRequest $request): ImportFileResponse;
}
