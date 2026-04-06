<?php

namespace OneToMany\LlmSdk\Action\SearchStore;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\SearchStore\ImportUploadedFileActionInterface;
use OneToMany\LlmSdk\Request\SearchStore\ImportUploadedFileRequest;
use OneToMany\LlmSdk\Response\SearchStore\ImportUploadedFileResponse;

final readonly class ImportFileAction extends BaseAction implements ImportUploadedFileActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\SearchStore\ImportUploadedFileActionInterface
     */
    public function act(ImportUploadedFileRequest $request): ImportUploadedFileResponse
    {
        return $this->createClient($request->getVendor())->stores()->importFile($request);
    }
}
