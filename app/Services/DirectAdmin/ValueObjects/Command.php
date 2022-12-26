<?php

declare(strict_types=1);

namespace App\Services\DirectAdmin\ValueObjects;

final class Command
{
    private function __construct(
        private string $method,
        private string $command,
        private ?array $body = null,
        private bool $json = false,
    ) {
    }

    public static function create(string $command): self
    {
        return new self(
            method: 'GET',
            command: $command,
        );
    }

    public function asPost(array $body): self
    {
        $self = clone $this;

        $self->method = 'POST';
        $self->body = $body;

        return $self;
    }

    public function method(): string
    {
        return $this->method;
    }

    public function command(): string
    {
        return $this->command;
    }

    public function body(): ?array
    {
        return $this->body;
    }
}
