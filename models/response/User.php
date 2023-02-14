<?php

namespace app\models\response;

use yii\base\Model;

class User extends Model
{
    public $user_id;
    public $telegram_id;
    public $fname;
    public $sname;
    public $login;

    public static function fromModel(\app\models\User $user)
    {
        return new static([
            'user_id' => $user->user_id,
            'telegram_id' => $user->telegram_id,
            'fname' => $user->fname,
            'sname' => $user->sname,
            'login' => $user->login,
        ]);
    }
}