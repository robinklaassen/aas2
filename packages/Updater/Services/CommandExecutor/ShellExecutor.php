<?php


namespace Updater\Services\CommandExecutor;
use Updater\Errors\ExecutorException;

class ShellExecutor implements ExecutorInterface
{
    public function execute(string $cmd, array $args = []): string
    {
        $line = $cmd . ' ' . implode(' ', $args);
        $descriptorspec = array(
           0 => array("pipe", "r"),
           1 => array("pipe", "w"),
           2 => array("pipe", "w"),
        );
        $process = proc_open($line . ' >&1', $descriptorspec, $pipes);
        if (is_resource($process)) {
            fclose($pipes[0]);

            $stdOut = stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $stdErr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            $result = proc_close($process);
        }

        if ($result !== 0) {
            throw ExecutorException::shellResult($result, $stdErr, $stdOut);
        }

        return $stdOut;
    }

}
