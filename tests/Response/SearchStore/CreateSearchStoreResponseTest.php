<?php

namespace OneToMany\LlmSdk\Tests\Response\SearchStore;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Response\SearchStore\CreateSearchStoreResponse;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function array_rand;
use function random_int;
use function uniqid;

#[Group('UnitTests')]
#[Group('ResponseTests')]
#[Group('SearchStoreTests')]
final class CreateSearchStoreResponseTest extends TestCase
{
    public function testJsonSerialize(): void
    {
        $vendor = Vendor::cases()[array_rand(Vendor::cases())];

        $json = [
            'vendor' => $vendor->getValue(),
            'uri' => uniqid('search_store_'),
            'size' => random_int(1, 1_048_576),
        ];

        $this->assertSame($json, new CreateSearchStoreResponse($vendor, $json['uri'], $json['size'])->jsonSerialize());
    }
}
