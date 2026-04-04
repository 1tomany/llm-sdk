<?php

namespace OneToMany\LlmSdk\Action\Store;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Store\CreateStoreActionInterface;
use OneToMany\LlmSdk\Request\Store\CreateStoreRequest;
use OneToMany\LlmSdk\Response\Store\CreateStoreResponse;

final readonly class CreateStoreAction extends BaseAction implements CreateStoreActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Store\CreateStoreActionInterface
     */
    public function act(CreateStoreRequest $request): CreateStoreResponse
    {
        return $this->createClient($request->getVendor())->stores()->create($request);
    }
}
