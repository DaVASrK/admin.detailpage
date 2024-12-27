<?php

return [
    'console' => [
        'value' => [
            'commands' => [
                \DVK\Admin\DetailPage\Cli\CreateDetailPageCommand::class,
            ],
        ],
        'readonly' => true,
    ],
];