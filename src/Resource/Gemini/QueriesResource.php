<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Content\Generation;
use OneToMany\LlmSdk\Resource\Gemini\Type\Embedding\Embedding;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;
use OneToMany\LlmSdk\Response\Query\Usage\UsageResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Stopwatch\Stopwatch;

use function array_merge;
use function sprintf;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $contentKey = $request->getModel()->isEmbedding() ? 'content' : 'contents';

        $requestContent = [
            $contentKey => [
                'parts' => [],
            ],
        ];

        // User Prompt Components
        foreach ($request->getPrompts() as $prompt) {
            $requestContent[$contentKey]['parts'][] = [
                'text' => $prompt->getPrompt(),
            ];
        }

        // File Prompt Components
        foreach ($request->getFiles() as $file) {
            $requestContent[$contentKey]['parts'][] = [
                'fileData' => [
                    'fileUri' => $file->getUri(),
                    'mimeType' => $file->getFormat(),
                ],
            ];
        }

        // Instructions Prompt Component
        if ($prompt = $request->getInstructions()) {
            $requestContent['systemInstruction']['parts'] = [
                [
                    'text' => $prompt->getPrompt(),
                ],
            ];
        }

        // Schema Prompt Component
        if ($schema = $request->getSchema()) {
            $requestContent['generationConfig'] = [
                'responseMimeType' => $schema->getFormat(),
                'responseJsonSchema' => $schema->getSchema(),
            ];
        }

        // Embedding Vector Dimensions Component
        if ($dimensionality = $request->getDimensions()) {
            $requestContent = array_merge($requestContent, [
                'outputDimensionality' => $dimensionality,
            ]);
        }

        return new CompileResponse($request->getModel(), $this->buildModelUrl($request->getModel()), $this->convertIfBatchRequest($request->getBatchKey(), $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     *
     * @throws RuntimeException when the model fails to generate any output
     */
    public function generate(ExecuteRequest $request): GenerateResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        /** @var array<string, mixed> $content */
        $content = $this->doPostRequest($request->getUrl(), [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$request->getRequest(),
            ],
        ]);

        $generation = $this->doDenormalize($content, Generation::class);

        if (!$output = $generation->getOutput()) {
            throw new RuntimeException(sprintf('The model "%s" failed to generate any output.', $request->getModel()->getName()));
        }

        return new GenerateResponse($request->getModel(), $generation->responseId, $output, $content, $timer->getDuration(), new UsageResponse($generation->usageMetadata->promptTokenCount, $generation->usageMetadata->cachedContentTokenCount, $generation->usageMetadata->outputTokenCount));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function embed(ExecuteRequest $request): EmbedResponse
    {
        $timer = new Stopwatch(true)->start('embed');

        $content = $this->doPostRequest($request->getUrl(), [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$request->getRequest(),
            ],
        ]);

        $embedding = $this->doDenormalize($content, Embedding::class, [
            UnwrappingDenormalizer::UNWRAP_PATH => '[embedding]',
        ]);

        return new EmbedResponse($request->getModel(), $embedding->values, $timer->getDuration());
    }

    /**
     * @return non-empty-string
     */
    private function buildModelUrl(Model $model): string
    {
        return $this->buildUrl($this->apiVersion, 'models', sprintf('%s:%s', $model->getId(), $model->isEmbedding() ? 'embedContent' : 'generateContent'));
    }

    /**
     * @param ?non-empty-string $batchKey
     * @param array<string, mixed> $request
     *
     * @return array<string, mixed>
     */
    private function convertIfBatchRequest(?string $batchKey, array $request): array
    {
        return null === $batchKey ? $request : ['key' => $batchKey, 'request' => $request];
    }
}
