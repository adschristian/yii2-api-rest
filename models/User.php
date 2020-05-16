<?php

namespace app\models;

use Ramsey\Uuid\Uuid;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Class User
 * @package app\models
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string $auth_key
 * @property string $access_token
 * @property string password_hash
 * @property integer created_at
 *
 * @property string password
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $password;
    
    /**
     * {@inheritDoc}
     */
    public static function tableName(): string
    {
        return 'users';
    }
    
    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'password'], 'required'],
            [['username'], 'string', 'max' => 32],
            [['email'], 'email'],
            [['password'], 'string', 'min' => 8, 'max' => 32],
            
        ];
    }
    
    /**
     * {@inheritDoc}
     */
    public function fields(): array
    {
        $fields = parent::fields();
        unset(
            $fields['auth_key'],
            $fields['access_token'],
            $fields['password_hash'],
            $fields['created_at'],
        );
        return $fields;
    }
    
    /**
     * @param int|string $id
     * @return User
     */
    public static function findIdentity($id): User
    {
        return self::findOne([
            'uuid' => $id
        ]);
    }
    
    /**
     * @param string $token
     * @param null $type
     * @return User
     */
    public static function findIdentityByAccessToken($token, $type = null): User
    {
        return self::findOne([
            'access_token' => $token
        ]);
    }
    
    /**
     * @return int|string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }
    
    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey): bool
    {
        return $this->auth_key === $authKey;
    }
    
    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password): bool
    {
        $security = \Yii::$app->security;
        return $security->validatePassword($password, $this->password);
    }
    
    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert): bool
    {
        if (parent::beforeSave($insert)) {
            $security = \Yii::$app->security;
            
            if ($this->isNewRecord) {
                $this->id = Uuid::uuid4()->toString();
                $this->auth_key = $security->generateRandomString();
                $this->access_token = $security->generateRandomString();
                
                $datetime = (new \DateTime('now'));
                $this->created_at = $datetime->getTimestamp();
            }
            
            $this->password_hash = $security->generatePasswordHash($this->password);
            
            return true;
        }
    
        return false;
    }
}
