<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\Content\UsageMetadata;
use OneToMany\LlmSdk\Contract\Client\QueryClientInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\Component\TextComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use OneToMany\LlmSdk\Response\Query\UsageResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

final readonly class QueryClient extends BaseClient implements QueryClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\QueryClientInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $url = $this->generateModelUrl($request->getModel(), 'generateContent');

        $requestContent = [
            'contents' => [],
        ];

        foreach ($request->getComponents() as $component) {
            if ($component instanceof TextComponent) {
                if ($component->getRole()->isSystem()) {
                    $requestContent['systemInstruction'] = [
                        'parts' => [
                            [
                                'text' => $component->getText(),
                            ],
                        ],
                    ];
                }

                if ($component->getRole()->isUser()) {
                    $requestContent['contents'][] = [
                        'parts' => [
                            [
                                'text' => $component->getText(),
                            ],
                        ],
                    ];
                }
            }

            if ($component instanceof FileUriComponent) {
                $requestContent['contents'][] = [
                    'parts' => [
                        [
                            'fileData' => [
                                'fileUri' => $component->getUri(),
                            ],
                        ],
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

        return new CompileResponse($request->getModel(), $url, $this->convertToBatchRequest($request->getBatchKey(), $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Client\QueryClientInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $timer = new Stopwatch(true)->start('execute');

        try {
            $response = $this->httpClient->request('POST', $request->getUrl(), [
                'headers' => [
                    'x-goog-api-key' => $this->getApiKey(),
                ],
                'json' => $request->getRequest(),
            ]);

            /**
             * @var array{
             *   candidates: non-empty-list<
             *     array{
             *       content: array{
             *         parts: non-empty-list<
             *           array{
             *             text: non-empty-string,
             *           },
             *         >,
             *         role: 'model',
             *       },
             *       finishReason: non-empty-uppercase-string,
             *       index: non-negative-int,
             *     },
             *   >,
             *   usageMetadata: array{
             *     promptTokenCount?: non-negative-int,
             *     cachedContentTokenCount?: non-negative-int,
             *     candidatesTokenCount?: non-negative-int,
             *     toolUsePromptTokenCount?: non-negative-int,
             *     thoughtsTokenCount?: non-negative-int,
             *     totalTokenCount?: non-negative-int,
             *   },
             *   modelVersion: non-empty-lowercase-string,
             *   responseId: non-empty-string,
             * } $responseContent
             */
            $responseContent = $response->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        } finally {
            $timer->stop();
        }

        try {
            $usage = $this->denormalizer->denormalize($responseContent, UsageMetadata::class, null, [
                UnwrappingDenormalizer::UNWRAP_PATH => '[usageMetadata]',
            ]);
        } catch (SerializerExceptionInterface) {
            $usage = new UsageMetadata();
        }

        return new ExecuteResponse(
            $request->getModel(),
            $responseContent['responseId'],
            $responseContent['candidates'][0]['content']['parts'][0]['text'],
            $responseContent,
            $timer->getDuration(),
            new UsageResponse(
                $usage->getInputTokens(),
                $usage->getCachedTokens(),
                $usage->getOutputTokens(),
            ),
        );
    }

    /**
     * @param ?non-empty-string $batchKey
     * @param array<string, mixed> $request
     *
     * @return array<string, mixed>
     */
    private function convertToBatchRequest(?string $batchKey, array $request): array
    {
        return null === $batchKey ? $request : ['key' => $batchKey, 'request' => $request];
    }
}
