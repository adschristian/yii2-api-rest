<?php

return [
    [
        'class' => \yii\rest\UrlRule::class,
        'prefix' => 'api/',
        'controller' => [
            'v1/user',
            'v1/post',
        ],
        'extraPatterns' => [
            'GET search' => 'search'
        ],
        'tokens' => [
            '{id}' => '<id:[\w-]+>',
        ]
    ],
];