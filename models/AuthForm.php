<?php

namespace app\models;

use Yii;
use app\repository\UserRepository;

class AuthForm extends \yii\base\Model
{
    public $phone;
    public $password;
    public $_user = false;


    public function rules()
    {
        return [
            [['phone', 'password'], 'required'],
            ['password', 'validatePassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'phone' => 'Номер телефона',
            'password' => 'Пароль'
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if(!$this->hasErrors()){
            $user = $this->getUser();
            if(!$user || !$user->validatePassword($this->password)){
                $this->addError($attribute, 'Неправильный номер или пароль!');
            }
        }
    }

    public function getUser()
    {
        if($this->_user === false){
            $this->_user = UserRepository::getUserByPhone($this->phone);
        }

        return $this->_user;
    }

    public function login()
    {
        if($this->validate()){
            return Yii::$app->user->login($this->getUser());
        }

        return false;
    }
}