<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Contract\Client\QueryClientInterface;
use OneToMany\AI\Request\Query\CompileRequest;
use OneToMany\AI\Request\Query\Component\FileUriComponent;
use OneToMany\AI\Request\Query\Component\SchemaComponent;
use OneToMany\AI\Request\Query\Component\TextComponent;
use OneToMany\AI\Request\Query\ExecuteRequest;
use OneToMany\AI\Response\Query\CompileResponse;
use OneToMany\AI\Response\Query\ExecuteResponse;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpClientExceptionInterface;

final readonly class QueryClient extends OpenAiClient implements QueryClientInterface
{
    /**
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $requestContent = [
            'model' => $request->getModel(),
            'input' => [],
        ];

        foreach ($request->getComponents() as $component) {
            if ($component instanceof TextComponent) {
                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => 'input_text',
                            'text' => $component->getText(),
                        ],
                    ],
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof FileUriComponent) {
                $fileType = \str_starts_with($component->getFormat(), 'image') ? 'input_image' : 'input_file';

                $requestContent['input'][] = [
                    'content' => [
                        [
                            'type' => $fileType,
                            'file_id' => $component->getUri(),
                        ],
                    ],
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof SchemaComponent) {
                $requestContent['text'] = [
                    'format' => [
                        'type' => 'json_schema',
                        'name' => $component->getName(),
                        'schema' => $component->getSchema(),
                        'strict' => $component->isStrict(),
                    ],
                ];
            }
        }

        return new CompileResponse($request->getModel(), $this->generateUrl('responses'), $requestContent);
    }

    /**
     * @see OneToMany\AI\Contract\Client\QueryClientInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $timer = new Stopwatch(true)->start('execute');

        try {
            $response = $this->httpClient->request('POST', $request->getUrl(), [
                'auth_bearer' => $this->apiKey,
                'json' => $request->getRequest(),
            ]);

            $responseContent = $response->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        }

        return new ExecuteResponse($request->getModel(), '', '', $responseContent, $timer->stop()->getDuration());
    }
}
