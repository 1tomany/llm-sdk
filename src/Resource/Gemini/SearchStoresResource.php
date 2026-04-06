<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\SearchStoresResourceInterface;
use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\FileSearchStore\CreateFileSearchStore;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\FileSearchStore\ImportFile;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\FileSearchStore;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\ImportFileOperation;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;
use OneToMany\LlmSdk\Response\SearchStore\ImportUploadedFileResponse;

use function sprintf;

final readonly class SearchStoresResource extends BaseResource implements SearchStoresResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\SearchStoresResourceInterface
     */
    public function create(CreateSearchStoreRequest $request): CreateSearchStoreResponse
    {
        $createFileSearchStore = new CreateFileSearchStore(...[
            'name' => $request->getName(),
        ]);

        $url = $this->buildUrl($this->getApiVersion(), 'fileSearchStores');

        $response = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$createFileSearchStore->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($response, FileSearchStore::class);

        return new CreateSearchStoreResponse($request->getVendor(), $object->name, $object->getSize());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\SearchStoresResourceInterface
     */
    public function importFile(ImportUploadedFileRequest $request): ImportUploadedFileResponse
    {
        $importFile = new ImportFile($request->getFileUri()->getUri());

        $url = $this->buildUrl($this->getApiVersion(), sprintf('%s:importFile', $request->getSearchStoreUri()));

        $response = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$importFile->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($response, ImportFileOperation::class);

        return new ImportUploadedFileResponse($request->getVendor(), $object->name);
    }
}
