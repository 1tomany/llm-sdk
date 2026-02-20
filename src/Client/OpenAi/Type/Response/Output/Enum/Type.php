<?php

namespace OneToMany\AI\Clients\Client\OpenAI\Type\Response\Output\Enum;

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
     * @return 'ApplyPatchCall'|'ApplyPatchCallOutput'|'CodeInterpreterCall'|'Compaction'|'ComputerCall'|'CustomToolCall'|'FileSearchCall'|'FunctionCall'|'ImageGenerationCall'|'LocalShellCall'|'McpApprovalRequest'|'McpCall'|'McpListTools'|'Message'|'Reasoning'|'ShellCall'|'ShellCallOutput'|'WebSearchCall'
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return 'apply_patch_call'|'apply_patch_call_output'|'code_interpreter_call'|'compaction'|'computer_call'|'custom_tool_call'|'file_search_call'|'function_call'|'image_generation_call'|'local_shell_call'|'mcp_approval_request'|'mcp_call'|'mcp_list_tools'|'message'|'reasoning'|'shell_call'|'shell_call_output'|'web_search_call'
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
