<?php


namespace app\modules\api\v1\controllers;

use app\models\search\PostSearch;
use app\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Class PostController
 * @package app\modules\api\v1\controllers
 */
class PostController extends ActiveController
{
    public $modelClass = \app\models\Post::class;
    
    /**
     * {@inheritDoc}
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        
        $behaviors['contentNegotiator']['formats'] = [
            'application/json' => Response::FORMAT_JSON
        ];
        
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'except' => ['index', 'view', 'search'],
            'rules' => [
                [
                    'allow' => true,
                    'actions' => ['create', 'update', 'delete'],
                    'roles' => ['@'],
                ],
            ]
        ];
        
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::class,
            'except' => ['index', 'view', 'search'],
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
     * SearchAction implements the API endpoint for searching a list of models from the given query params.
     * @return \yii\data\ActiveDataProvider
     */
    public function actionSearch(): ActiveDataProvider
    {
        $request = Yii::$app->request;
        $model = new PostSearch();
        return $model->search($request->queryParams);
    }
    
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete'])) {
            if ($model->created_by !== Yii::$app->user->id) {
                throw new ForbiddenHttpException(sprintf('You can only %s posts that you\'ve created.', $action));
            }
        }
    }
}