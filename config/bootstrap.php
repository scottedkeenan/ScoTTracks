<?php

use DI\ContainerBuilder;
use Slim\App;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

// Create DI ContainerBuilder instance
$containerBuilder = new ContainerBuilder();

// Set up settings
$containerBuilder->addDefinitions(__DIR__ . '/container.php');

// Build PHP-DI Container instance
$container = $containerBuilder->build();

// Create App instance

AppFactory::setContainer($container);
$app = AppFactory::create();

// Set custom error handler
// $app->addErrorMiddleware(false, false, false);

$customErrorHandler = function (
    \Slim\Psr7\Request $request,
    \Throwable $exception,
    bool $displayErrorDetails,
    bool $logErrors,
    bool $logErrorDetails
) use ($app) {
    // Log the error if needed
    if ($logErrors) {
        // Log the error using your preferred logging mechanism
    }

    // Customize the error response
    $response = $app->getResponseFactory()->createResponse();
//    $response->getBody()->write("You've landed out! (An error occurred). Please try again later.");

     // Extract information from the exception
     $message = $exception->getMessage();
     $statusCode = $exception->getCode();
     $file = $exception->getFile();
     $line = $exception->getLine();
     $stackTrace = $exception->getTraceAsString();

     // Prepare a readable error message
     $errorDetails = "Message: $message\n";
     $errorDetails .= "Code: $statusCode\n";
     $errorDetails .= "File: $file\n";
     $errorDetails .= "Line: $line\n";
     $errorDetails .= "Stack Trace:\n$stackTrace\n";

     // Write the error details to the response body
     $response->getBody()->write($errorDetails);

    // Set the content type and return the response with an appropriate status code
    return $response
        ->withHeader('Content-Type', 'text/plain')
        ->withStatus(500);
};

$app->addErrorMiddleware(true, false, false)->setDefaultErrorHandler($customErrorHandler);

// Register routes
(require __DIR__ . '/routes.php')($app);

// Register middleware
(require __DIR__ . '/middleware.php')($app);

return $app;
