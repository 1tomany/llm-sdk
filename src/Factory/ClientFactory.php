<?php

namespace OneToMany\LlmSdk\Factory;

use OneToMany\LlmSdk\Contract\Client\ClientInterface;
use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;

use function sprintf;

final readonly class ClientFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    /**
     * @throws InvalidArgumentException when a vendor does not have a client registered for it
     */
    public function create(Vendor $vendor): ClientInterface
    {
        try {
            $client = $this->container->get($vendor->getValue());
        } catch (ContainerExceptionInterface $e) {
        }

        if (!isset($client) || !$client instanceof ClientInterface) {
            throw new InvalidArgumentException(sprintf('The vendor "%s" does not have a client registered for it.', $vendor->getValue()), previous: $e ?? null);
        }

        return $client;
    }
}
