<?php

namespace OneToMany\LlmSdk\Resource\OpenAi;

use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Request\Query\ProcessQueryRequest;
use OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Response\Response;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;
use Symfony\Component\Stopwatch\Stopwatch;

final readonly class OutputsResource extends BaseResource implements OutputsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface
     */
    public function generate(ProcessQueryRequest $request): GenerateOutputResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        try {
            /** @var array<string, mixed> $response */
            $response = $this->doPostRequest($request->getUrl(), [
                'auth_bearer' => $this->getApiKey(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $object = $this->doDenormalize($response, Response::class);
        } finally {
            $timer->stop();
        }

        return new GenerateOutputResponse($request->getModel(), $object->id, $response, $object->getOutput(), $object->getError(), $timer->getDuration(), new TokenUsage($object->usage->getInputTokens(), $object->usage->getCachedTokens(), $object->usage->getOutputTokens()));
    }
}
