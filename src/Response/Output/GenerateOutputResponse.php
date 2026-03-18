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

final readonly class GenerateOutputResponse extends BaseResponse implements \JsonSerializable
{
    /**
     * @param non-empty-string $uri
     * @param array<string, mixed> $response
     * @param ?non-empty-string $output
     * @param ?non-empty-string $error
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

    /**
     * @return ?non-empty-string
     */
    public function getOutput(): ?string
    {
        return $this->output;
    }

    /**
     * @return ?non-empty-string
     */
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

    /**
     * @see \JsonSerializable
     *
     * @return array{
     *   model: non-empty-lowercase-string,
     *   vendor: non-empty-lowercase-string,
     *   uri: non-empty-string,
     *   output: ?non-empty-string,
     *   error: ?non-empty-string,
     *   runtime: non-negative-int,
     *   usage: TokenUsage,
     * }
     */
    public function jsonSerialize(): array
    {
        return [
            'model' => $this->getModel()->getValue(),
            'vendor' => $this->getVendor()->getValue(),
            'uri' => $this->getUri(),
            'output' => $this->getOutput(),
            'error' => $this->getError(),
            'runtime' => $this->getRuntime(),
            'usage' => $this->getUsage(),
        ];
    }
}
