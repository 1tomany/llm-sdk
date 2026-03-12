<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Content\GenerateContentResponse;
use OneToMany\LlmSdk\Resource\Gemini\Type\Content\Generation;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use OneToMany\LlmSdk\Response\Query\UsageResponse;
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

        if ($request->getModel()->isEmbedding() && $request->getDimensions()) {
            $requestContent['outputDimensionality'] = $request->getDimensions();
        }

        return new CompileResponse($request->getModel(), $this->buildModelUrl($request->getModel()), $this->convertIfBatchRequest($request->getBatchKey(), $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $timer = new Stopwatch(true)->start('execute');

        $content = $this->doPostRequest($request->getUrl(), [
            'headers' => $this->buildHeaders(),
            'json' => [
                ...$request->getRequest(),
            ],
        ]);

        $response = $this->doDenormalize($content, Generation::class);

        return new ExecuteResponse($request->getModel(), $response->responseId, $response->getOutput(), $content, $timer->getDuration(), new UsageResponse($response->usageMetadata->promptTokenCount, $response->usageMetadata->cachedContentTokenCount, $response->usageMetadata->outputTokenCount));
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
