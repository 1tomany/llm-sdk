<?php

namespace OneToMany\LlmSdk\Action\Store;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Store\ImportFileActionInterface;
use OneToMany\LlmSdk\Request\SearchStore\ImportSearchStoreFileRequest;
use OneToMany\LlmSdk\Response\Store\ImportFileResponse;

final readonly class ImportFileAction extends BaseAction implements ImportFileActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Store\ImportFileActionInterface
     */
    public function act(ImportSearchStoreFileRequest $request): ImportFileResponse
    {
        return $this->createClient($request->getVendor())->stores()->importFile($request);
    }
}
