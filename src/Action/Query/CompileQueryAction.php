<?php

namespace OneToMany\LlmSdk\Action\Query;

use OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface;
use OneToMany\LlmSdk\Contract\Request\Query\Component\Enum\Role;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;

use function sprintf;

final readonly class CompileQueryAction implements CompileQueryActionInterface
{
    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Action\Query\CompileQueryActionInterface
     *
     * @throws InvalidArgumentException when the request does not have any user components
     */
    public function act(CompileRequest $request): CompileResponse
    {
        if (!$request->hasUserComponents()) {
            throw new InvalidArgumentException(sprintf('Compiling the query failed because no components with the role "%s" have been added to it.', Role::User->getValue()));
        }

        return $this->clientFactory->create($request->getVendor())->queries()->compile($request);
    }
}
