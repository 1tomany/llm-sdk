<?php

namespace OneToMany\LlmSdk\Action\SearchStore;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\SearchStore\ReadSearchStoreActionInterface;
use OneToMany\LlmSdk\Request\SearchStore\ReadSearchStoreRequest;
use OneToMany\LlmSdk\Response\SearchStore\ReadSearchStoreResponse;

final readonly class ReadSearchStoreAction extends BaseAction implements ReadSearchStoreActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\SearchStore\ReadSearchStoreActionInterface
     */
    public function act(ReadSearchStoreRequest $request): ReadSearchStoreResponse
    {
        return $this->createClient($request->getVendor())->searchStores()->read($request);
    }
}
