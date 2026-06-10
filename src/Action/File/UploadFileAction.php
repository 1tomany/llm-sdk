<?php

namespace OneToMany\LlmSdk\Action\File;

use OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface;
use OneToMany\LlmSdk\Contract\Client\FileClientInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Transfer\Input\File\UploadFileInput;
use OneToMany\LlmSdk\Transfer\Output\File\FileOutput;

final readonly class UploadFileAction implements UploadFileActionInterface
{
    public function __construct(
        private ClientFactory $clientFactory,
    ) {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\File\UploadFileActionInterface
     */
    public function act(UploadFileInput $input): FileOutput
    {
        return $this->clientFactory->create($input->vendor, FileClientInterface::class)->uploadFile($input);
    }
}
