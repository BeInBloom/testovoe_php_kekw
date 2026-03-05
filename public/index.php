<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Domain\Contracts\LoggerInterface;
use App\Infrastructure\Container;
use App\Presentation\Controllers\IndexController;
use App\Presentation\Http\HttpErrorMapper;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$errorMapper = new HttpErrorMapper();
$logger = null;

try {
    $container = new Container();
    $logger = $container->get(LoggerInterface::class);

    $controller = $container->get(IndexController::class);

    $page = (int) ($_GET['page'] ?? 1);

    if ($page < 1) {
        throw new InvalidArgumentException('Page number must be positive');
    }

    $data = $controller($page);

    $latest = $data['latest'];
    $list = $data['list'];

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

    $errorMessage = $error->clientMessage;

    require __DIR__ . '/../app/Presentation/Views/error.php';
}
