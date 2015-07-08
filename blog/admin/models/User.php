<?php

namespace admin\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "alg_user".
 *
 * @property string $id
 * @property string $username
 * @property string $nickname
 * @property string $password
 * @property string $email
 * @property string $image
 * @property string $create_time
 * @property string $update_time
 * @property integer $is_del
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'alg_user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'create_time'], 'required'],
            [['create_time', 'update_time', 'is_del'], 'integer'],
            [['username', 'nickname', 'password', 'email'], 'string', 'max' => 64],
            [['image'], 'string', 'max' => 255],
            [['username'], 'unique'],
            [['email'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'nickname' => 'Nickname',
            'password' => 'Password',
            'email' => 'Email',
            'image' => 'Image',
            'create_time' => 'Create Time',
            'update_time' => 'Update Time',
            'is_del' => 'Is Del',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }
    
    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }
    
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }
    
    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return true;
    }
    
    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }
    
    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        $user = User::find()
            ->where(['username' => $username])
            ->asArray()
            ->one();

        if($user)
            return new static($user);

        return null;
    }
    
    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($username,$password)
    {
        $user = User::find()
            ->where(['username' => $username])
            ->asArray()
            ->one();
        if($user && $user['password'] == md5($password))
            return true;
        
        return false;
    }
    
}
