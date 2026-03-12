<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use Psr\Container\ContainerInterface;

final class ClientContainer implements ContainerInterface
{
    /**
     * @var array<non-empty-string, ClientInterface>
     */
    private array $clients = [];

    /**
     * @param array<non-empty-string, ClientInterface> $clients
     */
    public function __construct(array $clients = [])
    {
        $this->clients = $clients;
    }

    public function addClient(
        string|Vendor $vendor,
        ClientInterface $client,
    ): static
    {
        if ($vendor instanceof Vendor) {
            $vendor = $vendor->getValue();
        }

        if (!$vendor = \trim($vendor)) {
            throw new InvalidArgumentException('The vendor cannot be empty.');
        }

        $this->clients[\strtolower($vendor)] = $client;

        return $this;
    }

    /**
     * @see Psr\Container\ContainerInterface
     */
    public function get(string $id): ClientInterface
    {
        return $this->clients[$id] ?? throw new InvalidArgumentException(\sprintf('A client for the vendor "%s" could not be found.', $id));
    }

    /**
     * @see Psr\Container\ContainerInterface
     */
    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->clients);
    }
}
