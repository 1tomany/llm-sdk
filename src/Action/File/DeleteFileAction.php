<?php

namespace OneToMany\LlmSdk\Action\File;

use OneToMany\LlmSdk\Contract\Action\File\DeleteFileActionInterface;
use OneToMany\LlmSdk\Factory\FileClientFactory;
use OneToMany\LlmSdk\Request\File\DeleteRequest;
use OneToMany\LlmSdk\Response\File\DeleteResponse;

final readonly class DeleteFileAction implements DeleteFileActionInterface
{
    public function __construct(private FileClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\File\DeleteFileActionInterface
     */
    public function act(DeleteRequest $request): DeleteResponse
    {
        return $this->clientFactory->create($request->getModel())->delete($request);
    }
}
