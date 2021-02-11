<?php

namespace Updater\Errors;

class ExecutorException extends \RuntimeException
{
    public static function shellResult(int $resultCode, string $stdErr, string $stdOut): self
    {
        return new self(sprintf('Command failed, got resultCode %d, with output %s', $resultCode, '\n' . $stdErr));
    }

    public static function artisanResult(string $cmd, int $resultCode): self
    {
        return new self(sprintf('Artisan command %s failed, got resultCode %d', $cmd, $resultCode));
    }
}
