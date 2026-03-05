<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Domain\Contracts\LoggerInterface;
use App\Infrastructure\Container;
use App\Presentation\Controllers\NewsController;
use App\Presentation\Http\HttpErrorMapper;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->safeLoad();

$errorMapper = new HttpErrorMapper();
$logger = null;

try {
    $container = new Container();
    $logger = $container->get(LoggerInterface::class);

    $controller = $container->get(NewsController::class);

    $id = (int) ($_GET['id'] ?? 0);

    if ($id <= 0) {
        throw new InvalidArgumentException('News ID must be positive');
    }

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

    $errorMessage = $error->clientMessage;

    require __DIR__ . '/../app/Presentation/Views/error.php';
}
