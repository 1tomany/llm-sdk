<?php

namespace OneToMany\LlmSdk\Action\File;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\UploadFileResponse;

final readonly class UploadFileAction extends BaseAction implements UploadFileActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface
     */
    public function act(UploadRequest $request): UploadFileResponse
    {
        return $this->createClient($request->getVendor())->files()->upload($request);
    }
}
