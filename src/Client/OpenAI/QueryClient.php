<?php

namespace OneToMany\LlmSdk\Client\OpenAI;

use OneToMany\LlmSdk\Client\OpenAI\Type\Response\Input\Enum\Type as InputType;
use OneToMany\LlmSdk\Client\OpenAI\Type\Response\Response;
use OneToMany\LlmSdk\Contract\Client\QueryClientInterface;
use OneToMany\LlmSdk\Exception\RuntimeException;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\Component\TextComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;
use Symfony\Component\Stopwatch\Stopwatch;

use function parse_url;

use const PHP_URL_PATH;

final readonly class QueryClient extends BaseClient implements QueryClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\QueryClientInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $url = $this->generateUrl('responses');

        $requestContent = [
            'model' => $request->getModel(),
        ];

        foreach ($request->getComponents() as $component) {
            if (!isset($requestContent['input'])) {
                $requestContent['input'] = [];
            }

            if ($component instanceof TextComponent) {
                $inputType = InputType::InputText;

                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $inputType->getValue(),
                            'text' => $component->getText(),
                        ],
                    ],
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof FileUriComponent) {
                $inputType = InputType::InputFile;

                if ($component->isImage()) {
                    $inputType = InputType::InputImage;
                }

                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $inputType->getValue(),
                            'file_id' => $component->getUri(),
                        ],
                    ],
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof SchemaComponent) {
                $inputType = InputType::JsonSchema;

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

        return new CompileResponse($request->getModel(), $url, $this->convertToBatchRequest($request->getBatchKey(), $url, $requestContent));
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

        $response = $this->denormalize($content, Response::class);

        if (null !== $response->error) {
            throw new RuntimeException($response->error->getMessage());
        }

        return new ExecuteResponse($request->getModel(), $response->id, $response->getOutput(), $content, $timer->getDuration(), $response->usage->toResponse());
    }

    /**
     * @param ?non-empty-string $batchKey
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     *
     * @return array<string, mixed>
     */
    private function convertToBatchRequest(?string $batchKey, string $url, array $request): array
    {
        return null === $batchKey ? $request : ['custom_id' => $batchKey, 'method' => 'POST', 'url' => parse_url($url, PHP_URL_PATH), 'body' => $request];
    }
}
