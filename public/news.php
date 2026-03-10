<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Domain\Contracts\LoggerInterface;
use App\Infrastructure\Container;
use App\Presentation\Controllers\NewsController;
use App\Presentation\Http\HttpErrorMapper;
use App\Presentation\Http\QueryIntReader;

$errorMapper = new HttpErrorMapper();
$logger = null;

try {
    $container = require __DIR__ . '/../app/bootstrap.php';

    if (!$container instanceof Container) {
        throw new \RuntimeException('Application bootstrap did not return a container instance.');
    }

    $logger = $container->get(LoggerInterface::class);

    $controller = $container->get(NewsController::class);

    $id = QueryIntReader::positiveInt($_GET, 'id');

    $news = $controller->detail($id);

    require __DIR__ . '/../app/Presentation/Views/news.php';
} catch (\Throwable $exception) {
    $error = $errorMapper->map($exception);

    $context = [
        'exception' => $exception::class,
        'statusCode' => $error->statusCode,
        'newsId' => $id ?? null,
    ];

    if ($logger instanceof LoggerInterface) {
        if ($error->statusCode >= 500) {
            $logger->error('Unhandled exception on news page', $context);
        } else {
            $logger->warning('Invalid request on news page', $context);
        }
    } else {
        error_log('Unhandled exception on news page: ' . $exception::class);
    }

    http_response_code($error->statusCode);

    $statusCode   = $error->statusCode;
    $errorMessage = $error->clientMessage;

    require __DIR__ . '/../app/Presentation/Views/error.php';
}
