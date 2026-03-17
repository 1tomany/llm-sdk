<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Request\Batch;

use OneToMany\LlmSdk\Contract\Enum\Model;

use function sprintf;

final readonly class CreateBatch
{
    /**
     * @var non-empty-string
     */
    public string $endpoint;

    /**
     * @param non-empty-string $apiVersion
     * @param non-empty-string $inputFileId
     * @param non-empty-string $completionWindow
     */
    public function __construct(
        Model $model,
        string $apiVersion,
        public string $inputFileId,
        public string $completionWindow = '24h',
    ) {
        $this->endpoint = sprintf('/%s/%s', $apiVersion, $model->isEmbedding() ? 'embeddings' : 'responses');
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
