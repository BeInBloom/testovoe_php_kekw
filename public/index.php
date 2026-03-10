<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Domain\Contracts\LoggerInterface;
use App\Infrastructure\Container;
use App\Presentation\Controllers\IndexController;
use App\Presentation\Http\HttpErrorMapper;
use App\Presentation\Http\QueryIntReader;

$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($path) && $path !== '' ? $path : '/';

if ($path !== '/' && $path !== '/index.php') {
    http_response_code(404);
    $title = 'Страница не найдена';
    require __DIR__ . '/../app/Presentation/Views/not-found.php';

    return;
}

$errorMapper = new HttpErrorMapper();
$logger = null;

try {
    $container = require __DIR__ . '/../app/bootstrap.php';

    if (!$container instanceof Container) {
        throw new \RuntimeException('Application bootstrap did not return a container instance.');
    }

    $logger = $container->get(LoggerInterface::class);

    $controller = $container->get(IndexController::class);

    $page = QueryIntReader::positiveInt($_GET, 'page', 1);

    $data = $controller($page);

    $latest = $data['latest'];
    $list   = $data['list'];

    require __DIR__ . '/../app/Presentation/Views/index.php';
} catch (\Throwable $exception) {
    $error = $errorMapper->map($exception);

    $context = [
        'exception' => $exception::class,
        'statusCode' => $error->statusCode,
    ];

    if ($logger instanceof LoggerInterface) {
        if ($error->statusCode >= 500) {
            $logger->error('Unhandled exception on index page', $context);
        } else {
            $logger->warning('Invalid request on index page', $context);
        }
    } else {
        error_log('Unhandled exception on index page: ' . $exception::class);
    }

    http_response_code($error->statusCode);

    $statusCode   = $error->statusCode;
    $errorMessage = $error->clientMessage;

    require __DIR__ . '/../app/Presentation/Views/error.php';
}
