<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\GenerateIdTrait;
use OneToMany\LlmSdk\Resource\Mock\Type\Batch\Status;
use OneToMany\LlmSdk\Response\Batch\CreateBatchResponse;
use OneToMany\LlmSdk\Response\Batch\ReadBatchResponse;

final readonly class BatchesResource implements BatchesResourceInterface
{
    use GenerateIdTrait;

    public function __construct()
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateBatchResponse
    {
        return new CreateBatchResponse($request->getModel(), $this->generateId('batch'), Status::Processing->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadBatchResponse
    {
        return new ReadBatchResponse($request->getModel(), $request->getUri(), Status::Completed->getValue(), $this->generateId('file'));
    }
}
