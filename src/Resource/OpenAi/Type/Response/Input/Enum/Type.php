<?php

namespace OneToMany\LlmSdk\Resource\OpenAi\Type\Response\Input\Enum;

use OneToMany\LlmSdk\Contract\Request\Query\Component\ComponentInterface;
use OneToMany\LlmSdk\Request\Query\Component\FileUriComponent;
use OneToMany\LlmSdk\Request\Query\Component\PromptComponent;

enum Type: string
{
    case InputFile = 'input_file';
    case InputImage = 'input_image';
    case InputText = 'input_text';
    case JsonSchema = 'json_schema';

    /**
     * @return non-empty-string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return non-empty-lowercase-string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
