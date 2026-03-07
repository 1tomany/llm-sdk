<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface;
use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Resource\Mock\Trait\ResourceTrait;
use OneToMany\LlmSdk\Resource\Mock\Type\Batch\Status;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

final readonly class BatchesResource implements BatchesResourceInterface
{
    use ResourceTrait;

    public function __construct()
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function create(CreateRequest $request): CreateResponse
    {
        return new CreateResponse($request->getModel(), $this->generateId('batch'), Status::Processing->getValue());
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\BatchesResourceInterface
     */
    public function read(ReadRequest $request): ReadResponse
    {
        return new ReadResponse($request->getModel(), $request->getUri(), Status::Completed->getValue(), $this->generateId('file'));
    }
}
