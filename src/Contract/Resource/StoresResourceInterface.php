<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Response\SearchStore\CreateStoreResponse;
use OneToMany\LlmSdk\Response\SearchStore\ImportFileResponse;

interface StoresResourceInterface
{
    public function create(CreateSearchStoreRequest $request): CreateStoreResponse;

    public function importFile(ImportUploadedFileRequest $request): ImportFileResponse;
}
