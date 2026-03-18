<?php

namespace OneToMany\LlmSdk\Exception\Trait;

trait JsonSerializeTrait
{
    public function jsonSerialize(): array
    {
        return $this->serializeThrowable($this);
    }

    private function serializeThrowable(?\Throwable $throwable): ?array
    {
        if (!$throwable) {
            return null;
        }

        return [
            'message' => $throwable->getMessage(),
            'code' => $throwable->getCode(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'previous' => $this->serializeThrowable(...[
                'throwable' => $throwable->getPrevious(),
            ]),
        ];
    }
}
