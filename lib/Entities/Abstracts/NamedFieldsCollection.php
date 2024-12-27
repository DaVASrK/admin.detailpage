<?php

namespace DVK\Admin\DetailPage\Entities\Abstracts;

use DVK\Admin\DetailPage\Blocks\AbstractBlock;
use DVK\Admin\DetailPage\Contracts\ITemplate;
use DVK\Admin\DetailPage\Fields\AbstractField;

abstract class NamedFieldsCollection
{
    protected string $name;
    protected array  $fields;

    public function __construct(string $name, array $fields = [])
    {
        $this->name   = $name;
        $this->fields = $fields;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getFields(): array
    {
        return $this->fields;
    }


    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function setFields(array $fields): self
    {
        $this->validateFields($fields);
        $this->fields = $fields;

        return $this;
    }

    public function addField(AbstractField|AbstractBlock|ITemplate $field): self
    {
        $this->fields[] = $field;
        return $this;
    }


    protected function validateFields(array $fields): void
    {
        foreach ($fields as $field) {
            if (!$field instanceof AbstractField &&
                !$field instanceof AbstractBlock &&
                !$field instanceof ITemplate
            ) {
                throw new \InvalidArgumentException('Unsupported type of field');
            }
        }
    }
}
