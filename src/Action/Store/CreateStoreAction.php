<?php

namespace OneToMany\LlmSdk\Action\Store;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Store\CreateStoreActionInterface;
use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;

final readonly class CreateStoreAction extends BaseAction implements CreateStoreActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Store\CreateStoreActionInterface
     */
    public function act(CreateSearchStoreRequest $request): CreateSearchStoreResponse
    {
        return $this->createClient($request->getVendor())->stores()->create($request);
    }
}
