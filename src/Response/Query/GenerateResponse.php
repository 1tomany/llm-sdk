<?php

namespace OneToMany\LlmSdk\Response\Query;

use OneToMany\LlmSdk\Contract\Enum\Model;
use OneToMany\LlmSdk\Response\BaseResponse;

use function max;

readonly class GenerateResponse extends BaseResponse
{
    public function __construct(
        Model $model,
        private int|float $runtime = 0,
        private UsageResponse $usage = new UsageResponse(),
    ) {
        parent::__construct($model);
    }

    /**
     * @return non-negative-int
     */
    public function getRuntime(): int
    {
        return max(0, (int) $this->runtime);
    }

    public function getUsage(): UsageResponse
    {
        return $this->usage;
    }
}
