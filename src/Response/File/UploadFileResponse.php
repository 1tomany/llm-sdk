<?php

namespace OneToMany\LlmSdk\Response\File;

use OneToMany\LlmSdk\Contract\Enum\Vendor;

use function strtolower;

final readonly class UploadFileResponse implements \JsonSerializable
{
    private Vendor $vendor;

    /**
     * @param non-empty-string $uri
     * @param ?non-empty-string $name
     * @param ?non-empty-string $purpose
     */
    public function __construct(
        string|Vendor $vendor,
        private string $uri,
        private ?string $name = null,
        private ?string $purpose = null,
        private ?\DateTimeImmutable $expiresAt = null,
    ) {
        $this->vendor = Vendor::create($vendor);
    }

    public function getVendor(): Vendor
    {
        return $this->vendor;
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return ?non-empty-string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return ?non-empty-lowercase-string
     */
    public function getPurpose(): ?string
    {
        return $this->purpose ? strtolower($this->purpose) : null;
    }

    public function getExpiresAt(): ?\DateTimeImmutable
    {
        return $this->expiresAt;
    }

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   vendor: non-empty-lowercase-string,
     *   uri: non-empty-string,
     *   name: ?non-empty-string,
     *   purpose: ?non-empty-string,
     *   expiresAt: ?non-empty-string,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'vendor' => $this->getVendor()->getValue(),
            'uri' => $this->getUri(),
            'name' => $this->getName(),
            'purpose' => $this->getPurpose(),
            'expiresAt' => $this->getExpiresAt()?->format('c'),
        ];
    }
}
