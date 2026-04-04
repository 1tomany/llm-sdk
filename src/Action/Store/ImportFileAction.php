<?php

namespace OneToMany\LlmSdk\Action\Store;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\Store\ImportFileActionInterface;
use OneToMany\LlmSdk\Request\Store\ImportFileRequest;
use OneToMany\LlmSdk\Response\Store\ImportFileResponse;

final readonly class ImportFileAction extends BaseAction implements ImportFileActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\Store\ImportFileActionInterface
     */
    public function act(ImportFileRequest $request): ImportFileResponse
    {
        return $this->createClient($request->getVendor())->stores()->importFile($request);
    }
}
