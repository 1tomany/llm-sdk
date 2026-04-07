<?php

use OneToMany\LlmSdk\Action\Output\GenerateOutputAction;
use OneToMany\LlmSdk\Action\Query\CompileQueryAction;
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

    $compileQueryRequest = new CompileQueryRequest($model)->withPrompt($argv[3])->withTool(...[
        'tool' => new SearchStore($argv[2]),
    ]);

    $response = new CompileQueryAction($clientFactory)->act(...[
        'request' => $compileQueryRequest,
    ]);

    $response = new GenerateOutputAction($clientFactory)->act(...[
        'request' => $response->toProcessQueryRequest(),
    ]);
} catch (LlmSdkExceptionInterface $e) {
    $response = $e;
}

printf("%s\n", json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
exit($response instanceof Throwable ? 1 : 0);
