<?php

namespace OneToMany\LlmSdk\Response\Output;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Response\BaseResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;

use function json_decode;
use function max;
use function trim;

use const JSON_THROW_ON_ERROR;

final readonly class GenerateOutputResponse extends BaseResponse
{
    /**
     * @param non-empty-string $uri
     * @param array<string, mixed> $response
     */
    public function __construct(
        string|Model $model,
        private string $uri,
        private array $response,
        private ?string $output,
        private ?string $error = null,
        private int|float $runtime = 0,
        private TokenUsage $usage = new TokenUsage(),
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

    /**
     * @return array<string, mixed>
     */
    public function getResponse(): array
    {
        return $this->response;
    }

    public function getOutput(): ?string
    {
        return $this->output;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @return non-negative-int
     */
    public function getRuntime(): int
    {
        return max(0, (int) $this->runtime);
    }

    public function getUsage(): TokenUsage
    {
        return $this->usage;
    }

    /**
     * @return list<array<string, mixed>>|array<string, mixed>
     *
     * @throws RuntimeException when decoding the output from JSON to a record fails
     */
    public function toRecord(): array
    {
        try {
            /** @var list<array<string, mixed>>|array<string, mixed> $record */
            $record = json_decode(trim($this->output ?? ''), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new RuntimeException('Decoding the output from JSON to a record failed.', previous: $e);
        }

        return $record;
    }
}
