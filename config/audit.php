<?php

return [
    /**
     * Using with CLI
     */
    'cli' => true,

    /**
     * Audit table name
     */
    'table' => 'audits',

    /**
     * Queue settings
     * For default set to NULL
     */
    'queue' => [
        'connection' => null,
        'name'       => null,
    ],
];
