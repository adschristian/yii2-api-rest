<?php


namespace app\modules\api\v1\controllers;

use app\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class UserController
 * @package app\modules\api\v1\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = \app\models\User::class;
    
    /**
     * {@inheritDoc}
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset(
            $actions['index'],
            $actions['search'],
        );
        return $actions;
    }
    
    /**
     * {@inheritDoc}
     */
    public function behaviors():array
    {
        $behaviors = parent::behaviors();
        
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON
        ];
    
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'except' => ['create'],
            'auth' => function ($username, $password) {
                $user = User::find()->where(['username' => $username])->one();
                if ($user->validatePassword($password)) {
                    return $user;
                }
                return null;
            },
        ];
        
        return $behaviors;
    }
    
    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = []): void
    {
        if (in_array($action, ['update', 'delete', 'view'])) {
            if ($model->id !== \Yii::$app->user->id) {
                throw new ForbiddenHttpException(sprintf('You can only %s your own user.', $action));
            }
        }
        if ($action === 'create') {
            if (\Yii::$app->user->id === null) {
                throw new ForbiddenHttpException(sprintf('You cannot %s a user if you are logged in.', $action));
            }
        }
    }
}