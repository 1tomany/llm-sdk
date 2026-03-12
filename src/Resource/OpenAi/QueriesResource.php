<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Error\Error;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Input\Enum\Type;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Response;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\Content\EmbedResponse;
use OneToMany\LlmSdk\Response\Query\Content\GenerateResponse;
use OneToMany\LlmSdk\Response\Query\Usage\UsageResponse;
use Symfony\Component\Stopwatch\Stopwatch;

use function parse_url;

use const PHP_URL_PATH;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $url = $this->buildUrl($request->getModel()->isEmbedding() ? 'embeddings' : 'responses');

        $requestContent = [
            'model' => $request->getModel()->getId(),
        ];

        foreach ($request->getComponents() as $component) {
            if (!isset($requestContent['input'])) {
                $requestContent['input'] = [];
            }

            if ($component instanceof PromptComponent) {
                $inputType = Type::Text;

                if ($request->getModel()->isEmbedding()) {
                    $text = $component->getPrompt();

                    if ($component->getRole()->isUser()) {
                        $requestContent['input'][] = $text;
                    }
                } else {
                    $requestContent['input'][] = [
                        'content' => [
                            [
                                'type' => $inputType->getValue(),
                                'text' => $component->getPrompt(),
                            ],
                        ],
                        'role' => $component->getRole()->getValue(),
                    ];
                }
            } elseif ($component instanceof FileUriComponent) {
                $inputType = $component->isImage() ? Type::Image : Type::File;

                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $inputType->getValue(),
                            'file_id' => $component->getUri(),
                        ],
                    ],
                    'role' => $component->getRole()->getValue(),
                ];
            } elseif ($component instanceof SchemaComponent) {
                $inputType = Type::Schema;

                $requestContent['text'] = [
                    'format' => [
                        'type' => $inputType->getValue(),
                        'name' => $component->getName(),
                        'schema' => $component->getSchema(),
                        'strict' => $component->isStrict(),
                    ],
                ];
            }
        }

        if ($dimensions = $request->getDimensions()) {
            $requestContent['dimensions'] = $dimensions;
        }

        return new CompileResponse($request->getModel(), $url, $this->convertIfBatchRequest($request->getBatchKey(), $url, $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function generate(ExecuteRequest $request): GenerateResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        $content = $this->doPostRequest($request->getUrl(), [
            'auth_bearer' => $this->getApiKey(),
            'json' => [
                ...$request->getRequest(),
            ],
        ]);

        $response = $this->doDenormalize($content, Response::class);

        if ($response->error instanceof Error) {
            throw new RuntimeException($response->error->message);
        }

        return new GenerateResponse($request->getModel(), $response->id, $response->getOutput(), $content, $timer->getDuration(), new UsageResponse($response->usage->input_tokens, $response->usage->cached_tokens, $response->usage->output_tokens));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function embed(ExecuteRequest $request): EmbedResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        $content = $this->doPostRequest($request->getUrl(), [
            'auth_bearer' => $this->getApiKey(),
            'json' => [
                ...$request->getRequest(),
            ],
        ]);

        // print_r($content);
        // throw new \Exception('Not implemented');
    }

    /**
     * @param ?non-empty-string $batchKey
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     *
     * @return array<string, mixed>
     */
    private function convertIfBatchRequest(?string $batchKey, string $url, array $request): array
    {
        $url = parse_url($url, PHP_URL_PATH);

        if (!$batchKey || !$url) {
            return $request;
        }

        return ['custom_id' => $batchKey, 'method' => 'POST', 'url' => $url, 'body' => $request];
    }
}
