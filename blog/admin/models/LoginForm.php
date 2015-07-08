<?php
namespace admin\models;

use Yii;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $verifyCode;
    public $rememberMe = true;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password', 'verifyCode'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        $user = User::findByUsername($this->username);
        
        if (!$user || !$user->validatePassword($this->username,$this->password)) {
            $this->addError('password', 'Incorrect username or password.');
        }
    }
    
    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        return $user = User::findByUsername($this->username);
    }

}
