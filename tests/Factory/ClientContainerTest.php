<?php

namespace OneToMany\LlmSdk\Tests\Factory;

use OneToMany\LlmSdk\Factory\ClientContainer;
use OneToMany\LlmSdk\Factory\Exception\ContainerEntryNotFoundException;
use PHPUnit\Framework\TestCase;

final class ClientContainerTest extends TestCase
{
    public function testGettingEntryRequiresEntryToExist(): void
    {
        $this->expectException(ContainerEntryNotFoundException::class);
        $this->expectExceptionMessage('The entry "invalid" was not found in the container.');

        new ClientContainer()->get('invalid');
    }
}
