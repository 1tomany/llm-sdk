<?php

namespace OneToMany\LlmSdk\Action\File;

use OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\File\UploadRequest;
use OneToMany\LlmSdk\Response\File\UploadResponse;

final readonly class UploadFileAction implements UploadFileActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface
     */
    public function act(UploadRequest $request): UploadResponse
    {
        return $this->clientFactory->create($request->getVendor())->files()->upload($request);
    }
}
