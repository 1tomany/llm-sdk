<?php

namespace OneToMany\LlmSdk\Client\Anthropic;

use OneToMany\LlmSdk\Contract\Client\BatchClientInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class BatchClient extends BaseClient implements BatchClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        throw new RuntimeException('Not implemented!');
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\BatchClientInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        throw new RuntimeException('Not implemented!');
    }
}
