<?php

namespace OneToMany\AI\Client\OpenAi;

use OneToMany\AI\Client\OpenAi\Type\Response\Input\Enum\Type as InputType;
use OneToMany\AI\Client\OpenAi\Type\Response\Response;
use OneToMany\AI\Contract\Client\QueryClientInterface;
use OneToMany\AI\Exception\RuntimeException;
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
                'auth_bearer' => $this->getApiKey(),
                'json' => $request->getRequest(),
            ]);

            /**
             * @var array<string, mixed> $responseContent
             */
            $responseContent = $response->toArray(true);
        } catch (HttpClientExceptionInterface $e) {
            $this->handleHttpException($e);
        } finally {
            $timer->stop();
        }

        $output = $this->serializer->denormalize($responseContent, Response::class);

        if (null !== $output->error) {
            throw new RuntimeException($output->error->getMessage());
        }

        return new ExecuteResponse($request->getModel(), $output->id, $output->getOutput(), $responseContent, $timer->getDuration());
    }
}
