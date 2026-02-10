# AI and LLM Library for PHP

This library provides a single, unified, framework-independent library for integration with several popular AI platforms and large language models.

## Installation

Install the library using Composer:

```shell
composer require 1tomany/php-ai
```

## Usage

There are two ways to use this library:

1. **Direct** Instantiate the AI client you wish to use and send a request object to it. This method is easier to use, but comes with the cost that your application will be less flexible and testable.
2. **Actions** Register the clients you wish to use with a `OneToMany\AI\Factory\ClientFactory` instance, inject that instance into each action you wish to take, and interact with the action instead of through the client.

**Note:** A [Symfony bundle](https://github.com/1tomany/php-ai-bundle) is available if you wish to integrate this library into your Symfony applications with autowiring and configuration support.

I learn best by looking at actual code samples, so lets take a look at the two methods first.

### Direct usage

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\PropertyInfo\Extractor\ConstructorExtractor;
use Symfony\Component\PropertyInfo\Extractor\PhpDocExtractor;
use Symfony\Component\PropertyInfo\PropertyInfoExtractor;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\BackedEnumNormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

// Initialize the normalizers and serializer
$constructorExtractor = new ConstructorExtractor(...[
    'extractors' => [new PhpDocExtractor()]
]);

$typeExtractor = new PropertyInfoExtractor(...[
    'typeExtractors' => [$constructorExtractor]
]);

$objectNormalizer = new ObjectNormalizer(...[
    'propertyTypeExtractor' => $typeExtractor
]);

$this->serializer = new Serializer([
    new BackedEnumNormalizer(),
    new DateTimeNormalizer(),
    new ArrayDenormalizer(),
    $objectNormalizer,
]);

```



## Supported platforms

- Gemini
- Mock
- OpenAI

### Platform feature support

**Note:** Each platform refers to running model inference differently; OpenAI uses the word "Responses" while Gemini uses the word "Content". I've decided the word "Query" is the most succinct term to describe interacting with an LLM. The "Queries" section below refers to the ability to compile and execute a query against a large language model.

| Feature     | Gemini | Mock | OpenAI |
| ----------- | :----: | :--: | :----: |
| **Batches** |        |      |        |
| Create      |   ❌   |  ❌  |   ❌   |
| Read        |   ❌   |  ❌  |   ❌   |
| Cancel      |   ❌   |  ❌  |   ❌   |
| **Files**   |        |      |        |
| Upload      |   ✅   |  ✅  |   ✅   |
| Read        |   ❌   |  ❌  |   ❌   |
| List        |   ❌   |  ❌  |   ❌   |
| Download    |   ❌   |  ❌  |   ❌   |
| Delete      |   ✅   |  ✅  |   ✅   |
| **Queries** |        |      |        |
| Compile     |   ✅   |  ✅  |   ✅   |
| Execute     |   ✅   |  ✅  |   ✅   |

## Credits

- [Vic Cherubini](https://github.com/viccherubini), [1:N Labs, LLC](https://1tomany.com)

## License

The MIT License
