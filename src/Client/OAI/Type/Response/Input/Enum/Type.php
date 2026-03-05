<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Response\Input\Enum;

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
