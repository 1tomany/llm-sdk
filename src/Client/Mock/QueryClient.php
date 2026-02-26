<?php

namespace OneToMany\LlmSdk\Client\Mock;

use OneToMany\LlmSdk\Contract\Client\QueryClientInterface;
use OneToMany\LlmSdk\Request\Query\CompileRequest;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\SchemaComponent;
use OneToMany\LlmSdk\Request\Query\Component\TextComponent;
use OneToMany\LlmSdk\Request\Query\ExecuteRequest;
use OneToMany\LlmSdk\Response\Query\CompileResponse;
use OneToMany\LlmSdk\Response\Query\ExecuteResponse;

use function json_encode;
use function random_int;

final readonly class QueryClient extends BaseClient implements QueryClientInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Client\QueryClientInterface
     */
    public function compile(CompileRequest $request): CompileResponse
    {
        $url = $this->generateUrl('generate');

        $requestContent = [
            'model' => $request->getModel(),
        ];

        foreach ($request->getComponents() as $component) {
            if ($component instanceof TextComponent) {
                $requestContent['contents'][] = [
                    'text' => $component->getText(),
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
     * @see OneToMany\LlmSdk\Contract\Client\QueryClientInterface
     */
    public function execute(ExecuteRequest $request): ExecuteResponse
    {
        $id = $this->generateResponseId('query');

        /**
         * @var non-empty-string $output
         */
        $output = json_encode([
            'word1' => $this->faker->word(),
            'word2' => $this->faker->word(),
            'notes' => $this->faker->sentence(),
        ]);

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
