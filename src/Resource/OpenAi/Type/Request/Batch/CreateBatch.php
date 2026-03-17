<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Request\Batch;

use OneToMany\LlmSdk\Exception\InvalidArgumentException;

use function parse_url;

use const PHP_URL_PATH;

final readonly class CreateBatch
{
    public string $endpoint;

    /**
     * @param non-empty-string $endpoint
     * @param non-empty-string $inputFileId
     * @param non-empty-string $completionWindow
     */
    public function __construct(
        string $endpoint,
        public string $inputFileId,
        public string $completionWindow = '24h',
    ) {
        if (!$endpoint = parse_url($endpoint, PHP_URL_PATH)) {
            throw new InvalidArgumentException('The endpoint cannot be empty.');
        }

        $this->endpoint = $endpoint;
    }

    /**
     * @return array{
     *   endpoint: non-empty-string,
     *   input_file_id: non-empty-string,
     *   completion_window: non-empty-string,
     * }
     */
    public function toArray(): array
    {
        return [
            'endpoint' => $this->endpoint,
            'input_file_id' => $this->inputFileId,
            'completion_window' => $this->completionWindow,
        ];
    }
}
