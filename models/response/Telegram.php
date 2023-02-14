<?php

namespace app\models\response;

use yii\base\Model;
use yii\helpers\ArrayHelper;

class Telegram extends Model
{
    public $ok;
    public $result;
    public $description;

    public static function fromModel(array $telegram)
    {
        return new static([
            'ok' => ArrayHelper::getValue($telegram, 'ok', null),
            'result' => ArrayHelper::getValue($telegram, 'result', null),
            'description' => ArrayHelper::getValue($telegram, 'description', null)
        ]);
    }
}