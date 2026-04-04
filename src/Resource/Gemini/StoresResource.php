<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\StoresResourceInterface;
use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Request\Store\CreateStore;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\FileSearchStore\FileSearchStore;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;

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

        print_r($object);
        return new CreateStoreResponse($request->getVendor(), $object->name, $object->getSize());
    }
}
