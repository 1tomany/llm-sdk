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

### Examples

Review the examples below to get an idea of how the library works.

#### Embeddings

- [`examples/embeddings/create.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/embeddings/create.php) Creates an embedding vector from a prompt sent to an LLEM (large language embedding model)

#### Files

- [`examples/files/upload.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/files/upload.php) Uploads a file to an LLM vendor
- [`examples/files/deletes.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/files/delete.php) Deletes a file from an LLM vendor

#### Outputs

- [`examples/outputs/generate.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/outputs/generate.php) Generates output from a prompt sent to an LLM

#### Search Stores

- [`examples/search-stores/create.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/search-stores/create.php) Creates a search store for RAG support
- [`examples/search-stores/import-file.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/search-stores/import-file.php) Imports an uploaded file to an existing search store
- [`examples/search-stores/read.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/search-stores/read.php) Displays information about an existing search store
- [`examples/search-stores/search.php`](https://github.com/1tomany/llm-sdk/blob/master/examples/search-stores/searches.php) Searches an existing search store with a given prompt

## Supported platforms

- Anthropic
- Gemini
- Mock
- OpenAI

### Platform feature support

**Note:** Each platform refers to generating output (inference) differently; OpenAI uses the word "Responses" while Gemini uses the word "Content". I've decided the word "Output" best represents what a large language model produces in the case of generative models, and "Embedding" in the case of embedding models.

To generate output or create an embedding, you must first compile a "Query". A query is made up of different input components: text prompts, files, a JSON schema, and/or system instructions.

This library allows you to compile a query before sending it to the model for two reasons:

1. You can log/analyze the request payload before sending it to the model.
2. You can compile individual requests for batching.

| Feature           | Anthropic | Gemini | Mock | OpenAI |
| ----------------- | :-------: | :----: | :--: | :----: |
| **Batches**       |           |        |      |        |
| Create            |    ❌     |   ✅   |  ✅  |   ✅   |
| Read              |    ❌     |   ✅   |  ✅  |   ✅   |
| Cancel            |    ❌     |   ❌   |  ❌  |   ❌   |
| **Embeddings**    |           |        |      |        |
| Create            |    ❌     |   ✅   |  ✅  |   ✅   |
| **Files**         |           |        |      |        |
| Upload            |    ✅     |   ✅   |  ✅  |   ✅   |
| Read              |    ❌     |   ❌   |  ❌  |   ❌   |
| List              |    ❌     |   ❌   |  ❌  |   ❌   |
| Download          |    ❌     |   ❌   |  ❌  |   ❌   |
| Delete            |    ✅     |   ✅   |  ✅  |   ✅   |
| **Outputs**       |           |        |      |        |
| Generate          |    ❌     |   ✅   |  ✅  |   ✅   |
| **Queries**       |           |        |      |        |
| Compile           |    ❌     |   ✅   |  ✅  |   ✅   |
| **Search Stores** |           |        |      |        |
| Create            |    ❌     |   ✅   |  ❌  |   ❌   |
| Read              |    ❌     |   ✅   |  ❌  |   ❌   |
| Search            |    ❌     |   ✅   |  ❌  |   ❌   |
| ImportFile        |    ❌     |   ✅   |  ❌  |   ❌   |

## Credits

- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License

The MIT License
