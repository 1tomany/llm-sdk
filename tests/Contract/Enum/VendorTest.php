<?php

namespace OneToMany\LlmSdk\Tests\Contract\Enum;

use OneToMany\LlmSdk\Contract\Enum\Vendor;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

use function strtoupper;
use function uniqid;

#[Group('UnitTests')]
#[Group('ContractTests')]
#[Group('EnumTests')]
final class VendorTest extends TestCase
{
    #[DataProvider('providerEmptyVendorName')]
    public function testCreatingVendorRequiresNonEmptyVendorName(?string $vendor): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The vendor name cannot be empty.');

        Vendor::create($vendor);
    }

    /**
     * @return non-empty-list<non-empty-list<?string>>
     */
    public static function providerEmptyVendorName(): array
    {
        $provider = [
            [null],
            [''],
            [' '],
            ["\n"],
            [" \t\n "],
        ];

        return $provider;
    }

    public function testCreatingVendorRequiresValidName(): void
    {
        $vendor = uniqid('model_');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The vendor "'.$vendor.'" is not valid.');

        Vendor::create($vendor);
    }

    public function testCreatingVendor(): void
    {
        /** @var Vendor $vendor */
        $vendor = new \Random\Randomizer()->shuffleArray(Vendor::cases())[0];

        $this->assertSame($vendor, Vendor::create(strtoupper($vendor->getValue())));
    }
}
