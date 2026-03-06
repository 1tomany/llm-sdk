<?php

namespace OneToMany\LlmSdk\Resource\Mock;

use OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;

use function json_encode;
use function random_int;

final readonly class QueriesResource extends BaseResource implements QueriesResourceInterface
{
    private \Faker\Generator $faker;

    public function __construct()
    {
        $this->faker = \Faker\Factory::create();
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $url = $this->generateUrl('generate');

        $requestContent = [
            'model' => $request->getModel(),
        ];

        foreach ($request->getComponents() as $component) {
            if ($component instanceof PromptComponent) {
                $requestContent['contents'][] = [
                    'text' => $component->getPrompt(),
                    'role' => $component->getRole()->getValue(),
                ];
            }

            if ($component instanceof FileUriComponent) {
                $requestContent['contents'][] = [
                    'fileUri' => $component->getUri(),
                ];
            }

            if ($component instanceof SchemaComponent) {
                $requestContent['schema'] = [
                    'name' => $component->getName(),
                    'schema' => $component->getSchema(),
                    'format' => $component->getFormat(),
                ];
            }
        }

        return new CompileResponse($request->getModel(), $url, $this->convertToBatchRequest($request->getBatchKey(), $requestContent));
    }

    /**
     * @see OneToMany\LlmSdk\Contract\Resource\QueriesResourceInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $id = $this->generateResponseId('query');

        if (isset($request->getRequest()['schema'])) {
            /** @var non-empty-string $output */
            $output = json_encode(['output' => $this->faker->sentence()]);
        } else {
            $output = $this->faker->sentence(20);
        }

        return new ExecuteResponse($request->getModel(), $id, $output, ['id' => $id, 'output' => $output], random_int(100, 10000));
    }

    /**
     * @param ?non-empty-string $batchKey
     * @param array<string, mixed> $request
     *
     * @return array<string, mixed>
     */
    private function convertToBatchRequest(?string $batchKey, array $request): array
    {
        return null === $batchKey ? $request : ['batchKey' => $batchKey, 'request' => $request];
    }
}
