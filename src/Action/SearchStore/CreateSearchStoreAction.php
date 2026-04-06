<?php

namespace OneToMany\LlmSdk\Action\SearchStore;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\SearchStore\CreateSearchStoreActionInterface;
use OneToMany\LlmSdk\Request\SearchStore\CreateSearchStoreRequest;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;

final readonly class CreateSearchStoreAction extends BaseAction implements CreateSearchStoreActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\SearchStore\CreateSearchStoreActionInterface
     */
    public function act(CreateSearchStoreRequest $request): CreateSearchStoreResponse
    {
        return $this->createClient($request->getVendor())->stores()->create($request);
    }
}
