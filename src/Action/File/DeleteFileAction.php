<?php

namespace OneToMany\AI\Action\File;

use OneToMany\AI\Contract\Action\File\DeleteFileActionInterface;
use OneToMany\AI\Contract\Client\FileClientInterface;
use OneToMany\AI\Contract\Factory\ClientFactoryInterface;
use OneToMany\AI\Request\File\DeleteRequest;
use OneToMany\AI\Response\File\DeleteResponse;

final readonly class DeleteFileAction implements DeleteFileActionInterface
{
    /**
     * @param ClientFactoryInterface<FileClientInterface> $clientFactory
     */
    public function __construct(private ClientFactoryInterface $clientFactory)
    {
    }

    /**
     * @see OneToMany\AI\Contract\Action\File\DeleteFileActionInterface
     */
    public function act(DeleteRequest $request): DeleteResponse
    {
        return $this->clientFactory->create($request->getModel())->delete($request);
    }
}
