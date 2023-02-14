<?php

namespace app\models;

use yii\db\ActiveRecord;

class User extends ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_CREATE = 'create';

    public function rules()
    {
        return [
            [['login', 'telegram_id', 'access_token', 'access_token_expired_at'], 'required', 'on' => [self::SCENARIO_CREATE]],
            [['login'], 'unique', 'on' => [self::SCENARIO_CREATE]],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['login', 'telegram_id', 'access_token', 'access_token_expired_at', 'fname', 'sname'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return self::find()->where('user_id = :id', ['id' => $id])->one();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return self::find()
            ->where('access_token = :access_token', ['access_token' => $token])
            ->andWhere('access_token is not null')
            ->one();
    }

    public function refreshToken()
    {
        $this->access_token = \Yii::$app->security->generateRandomString();
        $this->access_token_expired_at = date('Y-m-d H:i:s', strtotime('now') + 604800);
        $this->save();
        $this->refresh();
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::find()
            ->where('login = :login', ['login' => trim($username)])
            ->one();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
