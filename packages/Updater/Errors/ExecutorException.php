<?php

namespace Updater\Errors;

class ExecutorException extends \RuntimeException
{
    public static function shellResult(string $cmd, int $resultCode, string $stdErr, string $stdOut): self
    {
        return new self(
            sprintf(
                'Command "%s" failed, got resultCode %d, with output %s',
                $cmd,
                $resultCode,
                '\n' . $stdErr
            )
        );
    }

    public static function artisanResult(string $cmd, int $resultCode): self
    {
        return new self(sprintf('Artisan command %s failed, got resultCode %d', $cmd, $resultCode));
    }
}
