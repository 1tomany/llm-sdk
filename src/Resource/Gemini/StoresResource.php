<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface;
use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\FileSearchStore\CreateFileSearchStore;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\FileSearchStore;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;

final readonly class StoresResource extends BaseResource implements StoresResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface
     */
    public function create(CreateStoreRequest $request): CreateStoreResponse
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

        return new CreateStoreResponse($request->getVendor(), $object->name, $object->getSize());
    }
}
