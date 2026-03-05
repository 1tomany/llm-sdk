<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Factory\ClientFactoryInterface;
use OneToMany\LlmSdk\Factory\Exception\CreatingClientFailedModelNotSupportedException;

use function sprintf;

/**
 * @template T of ClientInterface
 *
 * @implements ClientFactoryInterface<T>
 */
abstract readonly class BaseClientFactory implements ClientFactoryInterface
{
    /**
     * @param iterable<T> $clients
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
            if ($client->supportsModel($model)) {
                return $client;
            }
        }

        throw new CreatingClientFailedModelNotSupportedException($model);
    }
}
