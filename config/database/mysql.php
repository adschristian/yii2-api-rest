<?php

$name = getenv('DB_NAME');
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');

return [
    'class' => \yii\db\Connection::class,
    'dsn' => "mysql:host=$host;dbname=$name",
    'username' => $user,
    'password' => $pass,
    'charset' => 'utf8',

    // Schema cache options (for production environment)
    //'enableSchemaCache' => true,
    //'schemaCacheDuration' => 60,
    //'schemaCache' => 'cache',
];
