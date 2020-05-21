<?php

return [
    'components' => [
        'response' => [
            'class' => \yii\web\Response::class,
            'on beforeSend' => static function (\yii\base\Event $event) {
                /** @var \yii\web\Response $response */
                $response = $event->sender;
                if ($response->data !== null) {
                    $response->data = [
                        'success' => $response->isSuccessful,
                        'data' => $response->data,
                    ];
                    $response->statusCode = 200;
                }
            },
        ]
    ]
];