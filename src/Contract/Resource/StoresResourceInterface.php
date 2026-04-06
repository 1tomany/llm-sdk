<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;
use OneToMany\LlmSdk\Response\SearchStore\ImportUploadedFileResponse;

interface StoresResourceInterface
{
    public function create(CreateSearchStoreRequest $request): CreateSearchStoreResponse;

    public function importFile(ImportUploadedFileRequest $request): ImportUploadedFileResponse;
}
