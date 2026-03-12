<?php

namespace OneToMany\LlmSdk\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Response\BaseResponse;

use function json_decode;
use function max;
use function trim;

use const JSON_THROW_ON_ERROR;

final readonly class ExecuteResponse extends BaseResponse
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
        private int|float $runtime = 0,
        private UsageResponse $usage = new UsageResponse(),
    ) {
        parent::__construct($model);
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
     * @return non-negative-int
     */
    public function getRuntime(): int
    {
        return max(0, (int) $this->runtime);
    }

    public function getUsage(): UsageResponse
    {
        return $this->usage;
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
