<?php

namespace OneToMany\LlmSdk\Request\Output;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function sprintf;

class GenerateOutputRequest extends BaseRequest
{
    /**
     * @param non-empty-string $url
     * @param array<string, mixed> $request
     *
     * @throws InvalidArgumentException when the model is not a generative model
     */
    public function __construct(
        string|Model|null $model,
        private readonly string $url,
        private readonly array $request,
    ) {
        if (!($model = Model::create($model))->isGenerative()) {
            throw new InvalidArgumentException(sprintf('The model "%s" is not a generative model.', $model->getName()));
        }

        parent::__construct($model);
    }

    /**
     * @return non-empty-string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
