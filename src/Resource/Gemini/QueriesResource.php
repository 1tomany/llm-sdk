<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

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

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $url = $this->buildModelUrl($request->getModel(), $request->getModel()->isEmbedding() ? 'embedContent' : 'generateContent');

        if ($request->getModel()->isEmbedding()) {
            $requestContent = ['content' => []];

            foreach ($request->getPrompts() as $prompt) {
                $requestContent['content']['parts'][] = [
                    'text' => $prompt->getPrompt(),
                ];

                // Adjust the number of output dimensions
                if ($dimensionality = $request->getDimensions()) {
                    $requestContent = array_merge($requestContent, [
                        'outputDimensionality' => $dimensionality,
                    ]);
                }
            }
        } else {
            $requestContent = ['contents' => []];

            foreach ($request->getFiles() as $file) {
                $requestContent['contents']['parts'][] = [
                    'fileData' => [
                        'fileUri' => $file->getUri(),
                        'mimeType' => $file->getFormat(),
                    ],
                ];
            }

            foreach ($request->getPrompts() as $prompt) {
                $requestContent['contents']['parts'][] = [
                    'text' => $prompt->getPrompt(),
                ];
            }

            if ($prompt = $request->getInstructions()) {
                $requestContent['systemInstruction'] = [
                    'parts' => [
                        [
                            'text' => $prompt->getPrompt(),
                        ],
                    ],
                ];
            }

            if ($schema = $request->getSchema()) {
                $requestContent['generationConfig'] = [
                    'responseMimeType' => $schema->getFormat(),
                    'responseJsonSchema' => $schema->getSchema(),
                ];
            }
        }

        return new CompileResponse($request->getModel(), $url, $this->convertIfBatchRequest($request->getBatchKey(), $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     *
     * @throws RuntimeException when the model fails to generate any output
     */
    public function generate(ExecuteRequest $request): GenerateResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        $content = $this->doPostRequest($request->getUrl(), [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$request->getRequest(),
            ],
        ]);

        $generation = $this->doDenormalize($content, Generation::class);

        if (!$output = $generation->getOutput()) {
            throw new RuntimeException(\sprintf('The model "%s" failed to generate any output.', $request->getModel()->getName()));
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
