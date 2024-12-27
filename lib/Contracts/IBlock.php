<?php

namespace DVK\Admin\DetailPage\Contracts;

interface IBlock
{
    public function getId(): string;
    public function setId(string $id): self;

    public function onlyFor(array $values): self;
    public function deniedFor(array $values): self;

    public function getTemplate(): string;

    public function show(bool|\Closure $value = true): void;
}
