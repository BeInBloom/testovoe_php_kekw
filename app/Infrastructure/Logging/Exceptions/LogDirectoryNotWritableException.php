<?php

declare(strict_types=1);

namespace App\Infrastructure\Logging\Exceptions;

use RuntimeException;

final class LogDirectoryNotWritableException extends RuntimeException {}
