<?php

namespace OneToMany\LlmSdk\Request\Output;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Exception\InvalidArgumentException;
use OneToMany\LlmSdk\Request\BaseRequest;

use function sprintf;

class GenerateOutputRequest extends BaseRequest
{
    /**
     * @param array<string, mixed> $request
     *
     * @throws InvalidArgumentException when the model is not a generative model
     */
    public function __construct(
        string|Model|null $model,
        private readonly array $request,
    ) {
        $model = Model::create($model);

        if (!$model->isGenerative()) {
            throw new InvalidArgumentException(sprintf('The model "%s" is not a generative model.', $model->getValue()));
        }

        parent::__construct($model);
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequest(): array
    {
        return $this->request;
    }
}
