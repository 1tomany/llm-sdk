<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Factory\ClientFactoryInterface;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function sprintf;

/**
 * @template T of ClientInterface
 *
 * @implements ClientFactoryInterface<T>
 */
readonly class ClientFactory implements ClientFactoryInterface
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
     * @throws InvalidArgumentException when a client for the model `$model` is not registered
     */
    public function create(string $model): ClientInterface
    {
        foreach ($this->clients as $client) {
            if ($client->supportsModel($model)) {
                return $client;
            }
        }

        throw new InvalidArgumentException(sprintf('The model "%s" does not have a client registered.', $model));
    }
}
