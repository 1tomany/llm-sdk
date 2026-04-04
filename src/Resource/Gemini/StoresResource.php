<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface;
use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;
use OneToMany\LlmSdk\Request\Store\ImportFileRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store\CreateStore;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store\ImportFile;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\Document;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\FileSearchStore;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;
use OneToMany\LlmSdk\Response\Store\ImportFileResponse;

final readonly class StoresResource extends BaseResource implements StoresResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface
     */
    public function create(CreateStoreRequest $request): CreateStoreResponse
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

        return new CreateStoreResponse($request->getVendor(), $object->name, $object->getSize());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface
     */
    public function importFile(ImportFileRequest $request): ImportFileResponse
    {
        $importFile = new ImportFile(...[
            'fileName' => $request->getFileName(),
        ]);

        $url = $this->buildUrl($this->getApiVersion(), \sprintf('%s:importFile', $request->getStoreUri()));

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
