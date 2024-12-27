<?php

namespace DVK\Admin\DetailPage\Blocks;

class HtmlBlock extends AbstractBlock
{
    public string $content = '';

    public function __construct(string $content = '', string $id = null)
    {
        parent::__construct($id);

        $this->content = $content;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    public function getTemplate(): string
    {
        if (!$this->isCanShow()) { return ''; }

        ob_start();

        ?>
        <tr id="<?= $this->id ?>">
            <td colspan="2"><?= $this->content ?></td>
        </tr>
        <?

        return ob_get_clean();
    }
}
