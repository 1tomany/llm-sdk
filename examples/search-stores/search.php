<?php

use OneToMany\LlmSdk\Action\Output\GenerateOutputAction;
use OneToMany\LlmSdk\Contract\Exception\ExceptionInterface as LlmSdkExceptionInterface;
use OneToMany\LlmSdk\Factory\ClientFactory;
use OneToMany\LlmSdk\Request\Query\CompileQueryRequest;
use OneToMany\LlmSdk\Request\Type\Query\Tool\SearchStore;

/** @var ClientFactory $clientFactory */
$clientFactory = require dirname(__DIR__).'/bootstrap.php';

try {
    $model = trim($argv[1] ?? '') ?: 'mock';

    if (empty($argv[2] ?? null) || empty($argv[3] ?? null)) {
        printf("Usage: php %s <model> <search-store-uri> <prompt>\n", basename(__FILE__));
        exit(1);
    }

    // Compile a query to use an existing search store
    $compileQueryRequest = new CompileQueryRequest($model)->withPrompt($argv[3])->withTool(...[
        'tool' => SearchStore::create($argv[2]),
    ]);

    // Compile the query and send it to the LLM server
    $response = new GenerateOutputAction($clientFactory)->act(...[
        'request' => $compileQueryRequest,
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
