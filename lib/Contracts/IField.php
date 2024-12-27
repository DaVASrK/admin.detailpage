<?php

namespace DVK\Admin\DetailPage\Contracts;

interface IField
{
    public function getName(): string;
    public function getCode(): string;
    public function getValue(): mixed;
    public function getHint(): string;
    public function getTemplate(): string;

    public function name(string $value): self;
    public function value(mixed $value): self;
    public function hint(string|bool|\Closure $value): self;
    public function required(bool|\Closure $value): self;
    public function onlyFor(array $values): self;
    public function deniedFor(array $values): self;
    public function readonly(bool|\Closure $value = true): self;
    public function disabled(bool|\Closure $value = true): self;

    public function show(bool|\Closure $value = true): void;
}