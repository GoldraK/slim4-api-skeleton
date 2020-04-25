<?php

declare(strict_types=1);

use Psr\Http\Message\ServerRequestInterface;

$customErrorHandler = function (ServerRequestInterface $request, Throwable $exception, bool $displayErrorDetails, bool $logErrors, bool $logErrorDetails) use ($app) {
    $statusCode = 500;
    if (is_int($exception->getCode()) && $exception->getCode() >= 400 && $exception->getCode() <= 599) {
        $statusCode = $exception->getCode();
    }
    $className = new \ReflectionClass(get_class($exception));
    $data = [
        'message' => $exception->getMessage(),
        'class' => $className->getShortName(),
        'status' => 'error',
        'code' => $statusCode,
    ];
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));

    return $response->withStatus($statusCode)->withHeader('Content-type', 'application/json');
};
