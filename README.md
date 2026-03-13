# AI and LLM Library for PHP

This library provides a single, unified, framework-independent library for integration with several popular AI platforms and large language models.

## Installation

Install the library using Composer:

```shell
composer require 1tomany/llm-sdk
```

## Usage

There are two ways to use this library:

1. **Direct** Instantiate the AI client you wish to use and send a request object to it. This method is easier to use, but comes with the cost that your application will be less flexible and testable.
2. **Actions** Register the clients you wish to use with a `OneToMany\LlmSdk\Factory\ClientFactory` instance, inject that instance into each action you wish to take, and interact with the action instead of through the client.

**Note:** A [Symfony bundle](https://github.com/1tomany/llm-sdk-bundle) is available if you wish to integrate this library into your Symfony applications with autowiring and configuration support.

I learn best by looking at actual code samples, so lets take a look at the two methods first.

### Examples

- [`examples/files/upload.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/files/upload.php)
- [`examples/queries/execute.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/queries/execute.php)

## Supported platforms

- Anthropic
- Gemini
- Mock
- OpenAI

### Platform feature support

**Note:** Each platform refers to running model inference differently; OpenAI uses the word "Responses" while Gemini uses the word "Content". I've decided the word "Query" is the most succinct term to describe interacting with an LLM. The "Embeddings" and "Queries" sections below refers to the ability to compile a query and use it to generate output from an LLM.

| Feature         | Anthropic | Gemini | Mock | OpenAI |
| --------------- | :-------: | :----: | :--: | :----: |
| **Batches**     |           |        |      |        |
| Create          |    ❌     |   ✅   |  ✅  |   ✅   |
| Read            |    ❌     |   ✅   |  ✅  |   ✅   |
| Cancel          |    ❌     |   ❌   |  ❌  |   ❌   |
| **Embeddings**  |           |        |      |        |
| Compile         |    ❌     |   ✅   |  ✅  |   ✅   |
| Embed Content   |    ❌     |   ✅   |  ✅  |   ✅   |
| **Files**       |           |        |      |        |
| Upload          |    ✅     |   ✅   |  ✅  |   ✅   |
| Read            |    ❌     |   ❌   |  ❌  |   ❌   |
| List            |    ❌     |   ❌   |  ❌  |   ❌   |
| Download        |    ❌     |   ❌   |  ❌  |   ❌   |
| Delete          |    ✅     |   ✅   |  ✅  |   ✅   |
| **Queries**     |           |        |      |        |
| Compile         |    ❌     |   ✅   |  ✅  |   ✅   |
| Generate Output |    ❌     |   ✅   |  ✅  |   ✅   |

## Credits

- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License

The MIT License
