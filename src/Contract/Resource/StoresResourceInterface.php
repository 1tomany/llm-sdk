<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;
use OneToMany\LlmSdk\Response\Store\ImportFileResponse;

interface StoresResourceInterface
{
    public function create(CreateSearchStoreRequest $request): CreateStoreResponse;

    public function importFile(ImportUploadedFileRequest $request): ImportFileResponse;
}
