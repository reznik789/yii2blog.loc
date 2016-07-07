<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

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
class Users extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $hashPassword  = false;
    public $role = null;
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
            [['password'], 'string', 'min' => 6],
            [['first_name'], 'string', 'max' => 25],
            [['last_name'], 'string', 'max' => 30],
            [['authKey'], 'string', 'max' => 255],
            [['username'] , 'unique'],
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

    //---------------

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->authKey;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return \Yii::$app->security->validatePassword($password, $this->password);
        //return $password == $this->password;
    }

    //----------------

    public function beforeSave($insert)
    {

        if (parent::beforeSave($insert)) {
            if ($this->hashPassword) {
                $this->password = \Yii::$app->security->generatePasswordHash($this->password, 10);
            }
            $this->role = $this->getUserRole();
            //$this->upload();
            return true;
        } else {
            return false;
        }
    }


    
    public function afterSave($insert, $changedAttributes)
    {
        $auth = Yii::$app->authManager;
        if(!$insert) {
            $auth->revokeAll($this->id);
        }
            switch ($this->user_role) {
                case 3:
                    $role = $auth->getRole('admin');
                    break;
                case 2:
                    $role = $auth->getRole('editor');
                    break;
                default:
                    $role = $auth->getRole('user');
                    break;
            }
        $auth->assign($role, $this->id);
        parent::afterSave($insert, $changedAttributes); // TODO: Change the autogenerated stub
    }
}
