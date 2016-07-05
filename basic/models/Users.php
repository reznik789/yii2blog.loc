<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%users}}".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property integer $user_role
 * @property string $about
 * @property string $authKey
 *
 * @property Comments[] $comments
 * @property Posts[] $posts
 * @property Roles $userRole
 */
class Users extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'password', 'email', 'first_name', 'last_name'], 'required'],
            [['user_role'], 'integer'],
            [['about'], 'string'],
            [['username'], 'string', 'max' => 40],
            [['password', 'email'], 'string', 'max' => 100],
            [['first_name'], 'string', 'max' => 25],
            [['last_name'], 'string', 'max' => 30],
            [['authKey'], 'string', 'max' => 255],
            [['user_role'], 'exist', 'skipOnError' => true, 'targetClass' => Roles::className(), 'targetAttribute' => ['user_role' => 'id']],
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
            'password' => 'Password',
            'email' => 'Email',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'user_role' => 'User Role',
            'about' => 'About',
            'authKey' => 'Auth Key',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comments::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['author_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserRole()
    {
        return $this->hasOne(Roles::className(), ['id' => 'user_role']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->password = \Yii::$app->security->generatePasswordHash($this->password, 10);
            return true;
        } else {
            return false;
        }
    }
}
