<?php

namespace app\controllers;

use Yii;
use app\models\User;
use yii\rest\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use aki\telegram\base\Command;
use app\models\response\Telegram;
use yii\filters\ContentNegotiator;

class HookController extends Controller
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

    public function actionIndex(): void
    {
        Command::run("/start", function($telegram){
            $user = User::findByUsername($telegram->input->message->from->username);
            if(!$user){
                $user = new User();
                $user->setScenario(User::SCENARIO_CREATE);
                $user->setAttributes([
                    'login' => $telegram->input->message->from->username,
                    'fname' => $telegram->input->message->from->first_name,
                    'sname' => $telegram->input->message->from->last_name,
                    'telegram_id' => $telegram->input->message->from->id,
                    'access_token' => md5(\Yii::$app->security->generateRandomString()),
                    'access_token_expired_at' => date('Y-m-d H:i:s', strtotime('now') + 604800),
                ]);
                
                $telegram->sendMessage([
                    'chat_id' => $telegram->input->message->chat->id,
                    "text" => Yii::$app->params['baseUrl'] . '/site/login/' . $user->access_token
                ]);

                $user->save();
            } else {
                $telegram->sendMessage([
                    'chat_id' => $telegram->input->message->chat->id,
                    "text" => 'Вы уже зарегистрировались на платформе. Перейдите по ссылке: ' . Yii::$app->params['baseUrl'] 
                    .  '. Для повторной авторизации нужно прейти по ссылке ' . Yii::$app->params['baseUrl'] . '/site/login/' . $user->access_token
                ]);
            }
         });
    }

    public function actionSetWebHook(): Telegram
    {
        $telegram = Yii::$app->telegram->setWebhook([
            'url' => ArrayHelper::getValue(Yii::$app->params, 'url', null)
        ]);
        return Telegram::fromModel($telegram);
    }

    public function actionDelWebHook(): Telegram
    {
        $telegram = Yii::$app->telegram->deleteWebhook();
        return Telegram::fromModel($telegram);
    }

}
