<?php

namespace OneToMany\LlmSdk\Action\File;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\File\DeleteFileActionInterface;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Response\File\DeleteFileResponse;

final readonly class DeleteFileAction extends BaseAction implements DeleteFileActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\File\DeleteFileActionInterface
     */
    public function act(DeleteRequest $request): DeleteFileResponse
    {
        return $this->createClient($request->getVendor())->files()->delete($request);
    }
}
