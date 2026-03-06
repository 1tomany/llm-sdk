<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Factory\Exception\CreatingClientFailedModelNotSupportedException;

final readonly class ClientFactory
{
    /**
     * @param iterable<ClientInterface> $clients
     */
    public function __construct(private iterable $clients)
    {
    }

    /**
     * @param non-empty-lowercase-string $model
     *
     * @throws CreatingClientFailedModelNotSupportedException when a model is not supported by any clients
     */
    public function create(string $model): ClientInterface
    {
        foreach ($this->clients as $client) {
            if (\in_array($model, $client::getModels())) {
                return $client;
            }
        }

        throw new CreatingClientFailedModelNotSupportedException($model);
    }
}
