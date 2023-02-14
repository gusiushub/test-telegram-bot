<?php

namespace app\controllers;

use yii\rest\Controller;
use yii\web\Response;
use app\models\User;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;
use yii\web\NotFoundHttpException;
use app\models\response\BaseResponse;
use app\models\response\User as UserResponse;

class UserController extends Controller
{
    public function behaviors(): array
    {
        return [
            'contentNegotiator' => [
                'class' => ContentNegotiator::class,
                'formats' => [
                    'application/json' => Response::FORMAT_JSON,
                ],
            ],
            'verbFilter' => [
                'class' => VerbFilter::class,
                'actions' => $this->verbs(),
            ],
        ];
    }

    /**
     * @return array
     */
    protected function verbs(): array
    {
        return [
            'list' => ['GET'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionList(): array
    {
        $users = User::find();

        $pagination = new Pagination([
            'totalCount' => $users->count(),
        ]);

        $models = $users->limit($pagination->limit)
            ->offset($pagination->offset)
            ->all();

        $models = array_map(function($model){
            return UserResponse::fromModel($model);
        }, $models);

        return [
            'page' => $pagination->page + 1,
            'per_page' => $pagination->pageSize,
            'total_count' => (int)$pagination->totalCount,
            'last_page' => $pagination->pageCount - 1 <= $pagination->page,
            'users' => (array)$models,
        ];
    }

    public function actionDelete(int $id): BaseResponse
    {
        $model = User::find()->where(['user_id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException("User not found");
        }
        $model->delete();
        return new BaseResponse(['result' => 0]);
    }
}
