<?php

namespace app\models\response;

use yii\base\Model;

class BaseResponse extends Model
{
    public $result = 0;
    
    protected $_httpCode = 200;

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->_httpCode;
    }

    public function __construct(array $config = [], int $httpCode = 200)
    {
        $this->_httpCode = $httpCode;
        parent::__construct($config);
    }
}