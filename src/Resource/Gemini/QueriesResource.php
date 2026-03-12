<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Content\Embedding;
use OneToMany\LlmSdk\Resource\Gemini\Type\Content\Generation;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;
use OneToMany\LlmSdk\Response\Query\Usage\UsageResponse;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Stopwatch\Stopwatch;

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

        foreach ($request->getComponents() as $component) {
            if ($component instanceof PromptComponent) {
                if ($component->getRole()->isSystem()) {
                    $requestContent['systemInstruction'] = [
                        'parts' => [
                            [
                                'text' => $component->getPrompt(),
                            ],
                        ],
                    ];
                }

                if ($component->getRole()->isUser()) {
                    $requestContent[$contentKey]['parts'][] = [
                        'text' => $component->getPrompt(),
                    ];
                }
            }

            if ($component instanceof FileUriComponent) {
                $requestContent[$contentKey]['parts'][] = [
                    'fileData' => [
                        'fileUri' => $component->getUri(),
                        'mimeType' => $component->getFormat(),
                    ],
                ];
            }

            if ($component instanceof SchemaComponent) {
                $requestContent['generationConfig'] = [
                    'responseMimeType' => $component->getFormat(),
                    'responseJsonSchema' => $component->getSchema(),
                ];
            }
        }

        $url = $this->buildModelUrl($request->getModel(), $request->getModel()->isEmbedding() ? 'embedContent' : 'generateContent');

        if ($request->getModel()->isEmbedding() && $request->getDimensions()) {
            $requestContent['outputDimensionality'] = $request->getDimensions();
        }

        return new CompileResponse($request->getModel(), $url, $this->convertIfBatchRequest($request->getBatchKey(), $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
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

        return new GenerateResponse($request->getModel(), $generation->responseId, $generation->getOutput(), $content, $timer->getDuration(), new UsageResponse($generation->usageMetadata->promptTokenCount, $generation->usageMetadata->cachedContentTokenCount, $generation->usageMetadata->outputTokenCount));
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
