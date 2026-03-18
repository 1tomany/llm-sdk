<?php

namespace OneToMany\LlmSdk\Tests\Factory;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Factory\ClientFactory;
use PHPUnit\Framework\TestCase;

final class ClientFactoryTest extends TestCase
{
    public function testCreatingClientRequiresRegisteredClient(): void
    {
        /** @var Vendor $vendor */
        $vendor = new \Random\Randomizer()->shuffleArray(Vendor::cases())[0];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The vendor "'.$vendor->getValue().'" does not have a registered client.');

        new ClientFactory()->create($vendor);
    }
}
