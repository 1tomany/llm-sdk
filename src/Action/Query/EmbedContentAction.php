<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Action\BaseAction;
use OneToMany\LlmSdk\Action\Query\Trait\CompileQueryTrait;
use OneToMany\LlmSdk\Contract\Action\Query\EmbedContentActionInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;

final readonly class EmbedContentAction extends BaseAction implements EmbedContentActionInterface
{
    use CompileQueryTrait;

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\EmbedContentActionInterface
     */
    public function act(CompileRequest|ExecuteRequest $request): EmbedResponse
    {
        if ($request instanceof CompileRequest) {
            $request = $this->compileQuery($request)->toExecuteRequest();
        }

        return $this->createClient($request->getVendor())->queries()->embed($request);
    }
}
