<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Response\Input\Enum;

enum Type: string
{
    case InputFile = 'input_file';
    case InputImage = 'input_image';
    case InputText = 'input_text';
    case JsonSchema = 'json_schema';

    /**
     * @return 'InputFile'|'InputImage'|'InputText'|'JsonSchema'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'input_file'|'input_image'|'input_text'|'json_schema'
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
