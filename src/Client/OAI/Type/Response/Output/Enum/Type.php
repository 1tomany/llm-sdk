<?php

namespace OneToMany\LlmSdk\Client\OpenAI\Type\Response\Output\Enum;

enum Type: string
{
    case ApplyPatchCall = 'apply_patch_call';
    case ApplyPatchCallOutput = 'apply_patch_call_output';
    case CodeInterpreterCall = 'code_interpreter_call';
    case Compaction = 'compaction';
    case ComputerCall = 'computer_call';
    case CustomToolCall = 'custom_tool_call';
    case FileSearchCall = 'file_search_call';
    case FunctionCall = 'function_call';
    case ImageGenerationCall = 'image_generation_call';
    case LocalShellCall = 'local_shell_call';
    case McpApprovalRequest = 'mcp_approval_request';
    case McpCall = 'mcp_call';
    case McpListTools = 'mcp_list_tools';
    case Message = 'message';
    case Reasoning = 'reasoning';
    case ShellCall = 'shell_call';
    case ShellCallOutput = 'shell_call_output';
    case WebSearchCall = 'web_search_call';

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

    /**
     * @phpstan-assert-if-true self::Message $this
     */
    public function isMessage(): bool
    {
        return self::Message === $this;
    }
}
