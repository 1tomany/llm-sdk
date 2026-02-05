<?php

namespace OneToMany\AI\Action\File;

use OneToMany\AI\Contract\Action\File\UploadFileActionInterface;
use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Factory\ClientFactoryInterface;
use OneToMany\AI\Request\File\UploadRequest;
use OneToMany\AI\Response\File\UploadResponse;

final readonly class UploadFileAction implements UploadFileActionInterface
{
    /**
     * @param ClientFactoryInterface<FileClientInterface> $clientFactory
     */
    public function __construct(private ClientFactoryInterface $clientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\File\UploadFileActionInterface
     */
    public function act(UploadRequest $request): UploadResponse
    {
        return $this->clientFactory->create($request->getModel())->upload($request);
    }
}
