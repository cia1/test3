<?php

namespace common\models;

use common\queries\UserQuery;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property integer $statusId
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $verification_token
 * @property string $email
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 * @property string $first_name Имя
 * @property string $last_name Фамилия
 * @property string $phone Номер телефона
 * @property integer $rating Рейтинг
 *
 * @property Action[] $actions
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * @inheritDoc
     * @throws InvalidConfigException
     */
    public static function find(): UserQuery
    {
        /** @noinspection PhpIncompatibleReturnTypeInspection */
        return Yii::createObject(UserQuery::class, [get_called_class()]);
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['username', 'email', 'first_name', 'last_name'],
                'required'
            ],
            ['statusId', 'default', 'value' => Status::STATUS_REQUEST],
            ['statusId', 'in', 'range' => Status::STATUSES_ALL],
            ['rating', 'default', 'value' => 0],
            ['email', 'email'],
            ['first_name', 'match', 'pattern' => '/^[A-ZА-ЯЁё]+$/iu'],
            ['last_name', 'match', 'pattern' => '/^[A-ZА-ЯЁё]+$/iu'],
            ['phone', 'match', 'pattern' => '/^[0-9() +-]+$/'],
            ['rating', 'integer', 'min' => 0, 'max' => 16777215]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'statusId' => Status::STATUSES_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'statusId' => Status::STATUSES_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'statusId' => Status::STATUSES_ACTIVE,
        ]);
    }

    /**
     * Finds user by verification email token
     *
     * @param string $token verify email token
     * @return static|null
     */
    public static function findByVerificationToken($token)
    {
        return static::findOne([
            'verification_token' => $token,
            'statusId' => Status::STATUS_REGISTERED
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function generateEmailVerificationToken()
    {
        $this->verification_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /** @inheritDoc */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'email' => 'E-mail',
            'first_name' => 'Имя',
            'last_name' => 'Фамилия',
            'phone' => 'Телефон',
            'rating' => 'Рейтинг'
        ];
    }

    /**
     * Связь "Статус"
     * @return ActiveQuery
     */
    public function getStatus(): ActiveQuery
    {
        return $this->hasOne(Status::class, ['id' => 'statusId']);
    }

    /**
     * Связь "Действия пользователя"
     * @return ActiveQuery
     */
    public function getActions(): ActiveQuery
    {
        return $this->hasMany(Action::class, ['userId' => 'id']);
    }
}
