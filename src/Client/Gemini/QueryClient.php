<?php

namespace OneToMany\LlmSdk\Client\Gemini;

use OneToMany\LlmSdk\Client\Gemini\Type\Content\GenerateContentResponse;
use OneToMany\LlmSdk\Client\Gemini\Type\Content\UsageMetadata;
use OneToMany\LlmSdk\Contract\Client\QueryClientInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\Component\TextComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\Normalizer\UnwrappingDenormalizer;
use Symfony\Component\Stopwatch\Stopwatch;

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

        $content = $this->doRequest('POST', $request->getUrl(), [
            'json' => $request->getRequest(),
        ]);

        $response = $this->denormalize($content, GenerateContentResponse::class);

        // var_dump($response->getOutput());
        // exit;
        // $generateContentResponse->candidates[0]->content->parts[0]->text;

        // try {
        //     $usage = $this->denormalizer->denormalize($responseContent, UsageMetadata::class, null, [
        //         UnwrappingDenormalizer::UNWRAP_PATH => '[usageMetadata]',
        //     ]);
        // } catch (SerializerExceptionInterface) {
        //     $usage = new UsageMetadata();
        // }

        return new ExecuteResponse($request->getModel(), $response->responseId, $response->getOutput(), $content, $timer->getDuration(), $response->usageMetadata->toResponse());
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
