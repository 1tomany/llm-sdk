<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Factory\Exception\CreatingClientFailedModelNotSupportedException;

use function in_array;

final class ClientFactory
{
    /** @var list<ClientInterface> */
    private array $clients = [];

    /** @var array<non-empty-lowercase-string, ClientInterface> */
    private array $modelToClientMap = [];

    /**
     * @param iterable<ClientInterface> $clients
     */
    public function __construct(iterable $clients)
    {
        if ($clients instanceof \Traversable) {
            $clients = \iterator_to_array($clients);
        }

        $this->clients = \array_values($clients);
    }

    public function addClient(ClientInterface $client): static
    {
        if (!in_array($client, $this->clients, true)) {
            $this->clients[] = $client;
        }

        return $this;
    }

    /**
     * @param non-empty-lowercase-string $model
     *
     * @throws CreatingClientFailedModelNotSupportedException when a model is not supported by any clients
     */
    public function create(string $model): ClientInterface
    {
        if (!isset($this->modelToClientMap[$model])) {
            $client = \array_find($this->clients, fn ($c) => in_array($model, $c::getModels()));

            if (null === $client) {
                throw new CreatingClientFailedModelNotSupportedException($model);
            }

            $this->modelToClientMap[$model] = $client;
        }

        return $this->modelToClientMap[$model];
    }
}
