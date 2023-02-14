<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\web\Controller;

class SiteController extends Controller
{
    public function actionIndex()
    {
        $session = Yii::$app->session;

        if (!User::findIdentityByAccessToken($session->get('access_token'))) {
            return $this->render('unauthorized', ['content' => 'Пользователь не авторизован']);
        }

        return $this->render('authorized', ['content' => 'Пользователь авторизован']);
    }

    public function actionLogin(string $accessToken = null)
    {
        $session = Yii::$app->session;
        
        if (User::findIdentityByAccessToken($session->get('access_token'))) {
            return $this->goHome();
        }

        if (!empty($accessToken) && User::findIdentityByAccessToken($accessToken)) {
            $session->set('access_token', $accessToken);
            return $this->goHome();
        }

        return $this->render('unauthorized', [
            'content' => 'Пользователь не авторизован',
        ]);
    }
}
