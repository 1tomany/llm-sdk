<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface;
use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store\CreateStore;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store\ImportFile;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\Document\Document;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\FileSearchStore;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;
use OneToMany\LlmSdk\Response\SearchStore\ImportFileResponse;

use function sprintf;

final readonly class StoresResource extends BaseResource implements StoresResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface
     */
    public function create(CreateSearchStoreRequest $request): CreateSearchStoreResponse
    {
        $createStore = new CreateStore(...[
            'name' => $request->getName(),
        ]);

        $url = $this->buildUrl($this->getApiVersion(), 'fileSearchStores');

        $response = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$createStore->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($response, FileSearchStore::class);

        return new CreateSearchStoreResponse($request->getVendor(), $object->name, $object->getSize());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface
     */
    public function importFile(ImportUploadedFileRequest $request): ImportFileResponse
    {
        $importFile = new ImportFile($request->getFileUri()->getUri());

        $url = $this->buildUrl($this->getApiVersion(), sprintf('%s:importFile', $request->getSearchStoreUri()));

        $response = $this->doPostRequest($url, [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$importFile->toArray(),
            ],
        ]);

        $object = $this->doDenormalize($response, Document::class);

        return new ImportFileResponse($request->getVendor(), $object->name);
    }
}
