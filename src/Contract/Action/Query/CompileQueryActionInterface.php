<?php

namespace OneToMany\AI\Contract\Action\Query;

use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Response\Query\CompileResponse;

interface CompileQueryActionInterface
{
    public function act(CompileRequest $request): CompileResponse;
}
