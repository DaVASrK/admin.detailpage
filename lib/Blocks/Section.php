<?php

namespace DVK\Admin\DetailPage\Blocks;

class Section extends AbstractBlock
{
    protected string $text;

    public function __construct(string $text, string $id = null)
    {
        parent::__construct($id);
        $this->text = $text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function show(bool|\Closure $value = true): void
    {
        if (!$this->isCanShow($value)) { return; }

        $this->tabControl->addSection($this->id, $this->text);
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        return '<tr class="heading" id="tr_' . $this->id . '"><td colspan="2">' . $this->text . '</td></tr>';
    }
}
