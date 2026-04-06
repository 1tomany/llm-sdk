<?php

namespace OneToMany\LlmSdk\Contract\Resource;

use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Request\SearchStore\ReadSearchStoreRequest;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;
use OneToMany\LlmSdk\Response\SearchStore\ImportUploadedFileResponse;
use OneToMany\LlmSdk\Response\SearchStore\ReadSearchStoreResponse;

interface SearchStoresResourceInterface
{
    public function create(CreateSearchStoreRequest $request): CreateSearchStoreResponse;

    public function read(ReadSearchStoreRequest $request): ReadSearchStoreResponse;

    public function importFile(ImportUploadedFileRequest $request): ImportUploadedFileResponse;
}
