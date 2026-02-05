<?php

namespace OneToMany\AI\Contract\Action\Query;

use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\Query\ExecuteResponse;

interface ExecuteQueryActionInterface
{
    public function act(ExecuteRequest $request): ExecuteResponse;
}
