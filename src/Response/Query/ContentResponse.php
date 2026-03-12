<?php

namespace OneToMany\LlmSdk\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\RuntimeException;

use function json_decode;
use function trim;

use const JSON_THROW_ON_ERROR;

final readonly class ContentResponse extends ExecuteResponse
{
    /**
     * @param non-empty-string $uri
     * @param array<mixed> $response
     */
    public function __construct(
        Model $model,
        private string $uri,
        private string $output,
        private array $response = [],
        int|float $runtime = 0,
        UsageResponse $usage = new UsageResponse(),
    ) {
        parent::__construct($model, $runtime, $usage);
    }

    /**
     * @return non-empty-string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @return array<mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    /**
     * @return list<array<string, mixed>>|array<string, mixed>
     *
     * @throws RuntimeException when decoding the output fails
     */
    public function toRecord(): array
    {
        try {
            /** @var list<array<string, mixed>>|array<string, mixed> $record */
            $record = json_decode(trim($this->output), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException('Decoding the output failed.', previous: $e);
        }

        return $record;
    }
}
