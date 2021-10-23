<?php


namespace Updater\Services\CommandExecutor;

use Updater\Errors\ExecutorException;

class ShellExecutor implements ExecutorInterface
{
    public function execute(string $cmd, array $args = []): string
    {
        $line = $cmd . ' ' . implode(' ', $args);
        $descriptorspec = [
           0 => ["pipe", "r"],
           1 => ["pipe", "w"],
           2 => ["pipe", "w"],
        ];
        $process = proc_open($line, $descriptorspec, $pipes, base_path());
        if (is_resource($process)) {
            fclose($pipes[0]);

            $stdOut = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $stdErr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $result = proc_close($process);
        }

        if ($result !== 0) {
            throw ExecutorException::shellResult($cmd, $result, $stdErr, $stdOut, $cmd);
        }

        return $stdOut;
    }
}
