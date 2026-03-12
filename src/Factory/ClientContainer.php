<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
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
        foreach ($clients as $vendor => $client) {
            if ($client instanceof ClientInterface) {
                $this->addClient($vendor, $client);
            }
        }
    }

    public function addClient(
        string|Vendor $vendor,
        ClientInterface $client,
    ): static
    {
        if ($vendor instanceof Vendor) {
            $vendor = $vendor->getValue();
        }

        $this->clients[$vendor] = $client;

        return $this;
    }

    /**
     * @see Psr\Container\ContainerInterface
     */
    public function get(string $id): ClientInterface
    {
	throw new \Exception('Not implemented');
    }

    /**
     * @phpstan-assert-if-true ClientInterface $this->get()
     *
     * @see Psr\Container\ContainerInterface
     */
    public function has(string $id): bool
    {
        return \array_key_exists($id, $this->clients);
    }
}
