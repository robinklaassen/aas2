<?php


namespace Updater\Services\CommandExecutor;
use Updater\Errors\ExecutorException;

class ShellExecutor implements ExecutorInterface
{
    public function execute(string $cmd, array $args = []): string
    {
        exec($cmd . ' ' . implode(' ', $args), $output, $result);

        if ($result !== 0) {
            return $output;
        }

        throw ExecutorException::shellResult($result, $output);
    }

}
