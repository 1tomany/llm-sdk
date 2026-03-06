<?php

namespace OneToMany\LlmSdk\Contract\Client;

use OneToMany\LlmSdk\Request\Batch\CreateRequest;
use OneToMany\LlmSdk\Request\Batch\ReadRequest;
use OneToMany\LlmSdk\Response\Batch\CreateResponse;
use OneToMany\LlmSdk\Response\Batch\ReadResponse;

interface BatchClientInterface
{
    public function create(CreateRequest $request): CreateResponse;

    public function read(ReadRequest $request): ReadResponse;
}
