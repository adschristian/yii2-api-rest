<?php


namespace app\modules\api\v1\controllers;


class UserController extends \yii\rest\ActiveController
{
    public $modelClass = \app\models\User::class;
}