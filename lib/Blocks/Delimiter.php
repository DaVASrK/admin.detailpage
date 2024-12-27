<?php

namespace DVK\Admin\DetailPage\Blocks;

class Delimiter extends AbstractBlock
{
    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        return '<tr><td colspan="2"><div style="background: #c4ced2; height: 1px; width: 100%; margin: 20px 0"></div></td></tr>';
    }
}
