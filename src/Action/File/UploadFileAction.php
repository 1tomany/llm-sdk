<?php

namespace OneToMany\LlmSdk\Action\File;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class UploadFileAction extends BaseAction implements UploadFileActionInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface
     */
    public function act(UploadRequest $request): UploadResponse
    {
        return $this->createClient($request->getVendor())->files()->upload($request);
    }
}
