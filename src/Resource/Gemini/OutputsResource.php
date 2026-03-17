<?php

namespace OneToMany\LlmSdk\Resource\Gemini;

use OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface;
use OneToMany\LlmSdk\Request\Output\GenerateOutputRequest;
use OneToMany\LlmSdk\Resource\Gemini\Type\Response\Content\Generation;
use OneToMany\LlmSdk\Response\Output\GenerateOutputResponse;
use OneToMany\LlmSdk\Response\Usage\TokenUsage;
use Symfony\Component\Stopwatch\Stopwatch;

final readonly class OutputsResource extends BaseResource implements OutputsResourceInterface
{
    /**
     * @see OneToMany\LlmSdk\Contract\Resource\OutputsResourceInterface
     */
    public function generate(GenerateOutputRequest $request): GenerateOutputResponse
    {
        $timer = new Stopwatch(true)->start('generate');

        try {
            /** @var array<string, mixed> $response */
            $response = $this->doPostRequest($request->getUrl(), [
                'headers' => $this->buildHeaders(),
                'json' => [
                    ...$request->getRequest(),
                ],
            ]);

            $object = $this->doDenormalize($response, Generation::class);
        } finally {
            $timer->stop();
        }

        return new GenerateOutputResponse($request->getModel(), $object->responseId, $response, $object->getOutput(), null, $timer->getDuration(), new TokenUsage($object->usageMetadata->promptTokenCount, $object->usageMetadata->cachedContentTokenCount, $object->usageMetadata->outputTokenCount));
    }
}
