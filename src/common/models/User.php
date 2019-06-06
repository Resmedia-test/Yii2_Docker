<?php
namespace common\models;

use common\components\ImageAttachmentBehavior;
use frontend\components\MetaTagBehavior;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\helpers\VarDumper;
use yii\rbac\Assignment;
use yii\rbac\Item;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $role
 * @property string $name
 * @property string $email
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $activation_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $last_login
 * @property string $password write-only password
 * @property string $roleName
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $fullName;
    public $password;
    public $passwordRepeat;
    public $passwordOld;
    public $activation_token;

    public $role;
    public $termsOfUse;

    const SESSION_CLIENT = 'session_client';

    const ROLE_ADMIN = "admin";
    const ROLE_USER = "user";
    const ROLE_GUEST = "guest";

    const SCENARIO_SIGNUP = 'signup';
    //const SCENARIO_SIGNUP_SMALL = 'signup-small';
    const SCENARIO_SIGNUP_FAST = 'signup-fast';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_RECOVERY = 'recovery';
    

    const STATUS_INACTIVE   = 0;
    const STATUS_EMAIL_NC   = 2;
    const STATUS_ACTIVE     = 1;
    const STATUS_DELETED    = -1;

    static $roles = [
        self::ROLE_ADMIN => 'Администратор',
        self::ROLE_USER => 'Пользователь',
        self::ROLE_GUEST => 'Гость',
    ];

    static $statuses = [
        self::STATUS_ACTIVE => 'Активный',
        self::STATUS_EMAIL_NC => 'Email не активирован',
        self::STATUS_INACTIVE => 'Неактивный',
        self::STATUS_DELETED => 'Удален',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();

        return array_merge(
            $scenarios,
            [
                self::SCENARIO_SIGNUP => ['name', 'email', 'password', 'passwordRepeat'],
                self::SCENARIO_UPDATE => [
                    'name', 'email',
                    'roleName', 'activation_token', 'status',
                    'password', 'passwordOld'
                ],
                self::SCENARIO_UPDATE_PASSWORD => ['password', 'passwordOld'],
                self::SCENARIO_RECOVERY => ['email'],
                self::SCENARIO_SIGNUP_FAST => ['name', 'email'],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'MetaTag' => [
                'class' => MetaTagBehavior::class,
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'role'], 'required'],

            ['email', 'email'],
            ['email', 'unique', 'message' => 'Этот email у нас уже есть!'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::$statuses)],

            [['password', 'password_hash', 'email', 'password_reset_token', 'activation_token','roleName'], 'string'],
            ['role', 'in', 'range' => array_keys(self::$roles)],

            /*[
                ['password', 'passwordOld'], 'required',
                'on' => self::SCENARIO_UPDATE_PASSWORD
            ],*/
          
            //SCENARIO_SIGNUP_FAST
            [
                ['email', 'name'], 'required',
                'on' => self::SCENARIO_SIGNUP_FAST
            ],

            //SCENARIO_SIGNUP
            [
                ['email', 'name', 'password', 'passwordRepeat'], 'required',
                'on' => self::SCENARIO_SIGNUP
            ],
            [
                'passwordRepeat', 'compare', 'compareAttribute' => 'password',
                'message' => 'Пароли не совпадают',
                'on' => self::SCENARIO_SIGNUP
            ],

            [['id', 'status', 'name', 'last_login', 'role'], 'safe'],
        ];
    }

    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id'])->from(AuthAssignment::tableName() . ' assignment');
    }
    
    /**
     * @return array
     */
    public static function getUserStatuses()
    {
        return self::$statuses;
    }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'role' => 'Роль',
            'status' => 'Статус',
            'roleName' => 'Имя роли',
            'name' => 'Имя',
            'password' => 'Пароль',
            'passwordOld' => 'Старый пароль',
            'username' => 'Email',
        ];
    }
    /**
     * @return User
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => [self::STATUS_ACTIVE, self::STATUS_EMAIL_NC]]);
    }

    /**
     * @inheritdoc
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
        return static::findOne(['email' => $username, 'status' => [self::STATUS_ACTIVE, self::STATUS_EMAIL_NC]]);
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
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function findByActivationToken($token)
    {
        if (!static::isActivationTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'activation_token' => $token,
            'status' => [self::STATUS_INACTIVE, self::STATUS_EMAIL_NC],
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public static function isActivationTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.activationTokenExpire'];
        return $timestamp + $expire >= time();
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
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
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
        $this->password = $password;
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

    public function generateActivationToken()
    {
        $this->activation_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function activate()
    {
        $this->activation_token = "";
        $this->status = self::STATUS_ACTIVE;
        $this->setPassword( Yii::$app->security->generateRandomString(10) );
        $this->updateAttributes(['activation_token', 'status', 'password_hash']);

        \Yii::$app->mailer->compose(['html' => 'activationPassword-html'], ['user' => $this])
            ->setFrom(['post@' . $_SERVER['HTTP_HOST'] => 'TestSite'])
            ->setTo($this->email)
            ->setSubject('Пароль от профиля TestSite')
            ->send();
    }

    public function activateEmail()
    {
        $this->activation_token = "";
        $this->status = self::STATUS_ACTIVE;
        $this->updateAttributes(['activation_token', 'status']);
    }

    public function changePassword()
    {
        $this->removePasswordResetToken();
        $this->status = self::STATUS_ACTIVE;
        $this->setPassword( Yii::$app->security->generateRandomString(10) );
        $this->updateAttributes(['password_reset_token', 'status', 'password_hash']);

        \Yii::$app->mailer->compose(['html' => 'activationPassword-html'], ['user' => $this])
            ->setFrom(['post@' . $_SERVER['HTTP_HOST'] => 'TestSite'])
            ->setTo($this->email)
            ->setSubject('Пароль от профиля TestSite')
            ->send();
    }

    public function afterFind()
    {
        parent::afterFind();

        $roles = Yii::$app->authManager->getRolesByUser($this->id);
        $role = reset($roles);

        if ($role) {
            $this->role = $role->name;
        }

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        //update role
        if ($this->role) {
            $roles = Yii::$app->authManager->getRolesByUser($this->id);
            $role = reset($roles);

            if (!$insert && $role && $role != $this->role) {
                Yii::$app->authManager->revoke($role, $this->id);
            }

            $newRole = Yii::$app->authManager->getRole($this->role);

            if (isset($newRole)) {
                Yii::$app->authManager->assign($newRole, $this->id);
            }
        }

        //sending activation email
        if ($insert && (!empty($this->activation_token) && $this->status == self::STATUS_EMAIL_NC)) {
            //check if activation token exists and valid
            if (!self::isActivationTokenValid($this->activation_token)) {
                $this->generateActivationToken();
                $this->updateAttributes(['activation_token']);
            }

            //send activation email
            \Yii::$app->mailer->compose(['html' => 'activationTokenEmail-html'], ['user' => $this])
                ->setFrom(['post@' . $_SERVER['HTTP_HOST'] => 'TestSite'])
                ->setTo($this->email)
                ->setSubject('Активация аккаунта на сайте TestSite')
                ->send();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        if (!empty($this->password))
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);

        //setting activation key
        if ($insert && 
            $this->role == self::ROLE_USER && 
            !$this->password_hash && 
            $this->scenario == self::SCENARIO_SIGNUP_FAST) {
            
            $this->generateActivationToken();
            $this->status = self::STATUS_INACTIVE;

            //send activation email
            \Yii::$app->mailer->compose(['html' => 'activationToken-html'], ['user' => $this])
                ->setFrom(['post@' . $_SERVER['HTTP_HOST'] => 'Test site'])
                ->setTo($this->email)
                ->setSubject('Активация аккаунта на сайте Test site')
                ->send();
        }

        return parent::beforeSave($this->isNewRecord);
    }

    public function search($params=null)
    {
        $query = User::find();

        $query->joinWith(['assignment']);
        
        if (isset($params)) {
            $this->load($params);
        }

        //adjust the query by adding the filters
        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name',$this->name]);
        $query->andFilterWhere(['like', 'email',$this->email]);
        $query->andFilterWhere(['last_login' => $this->last_login]);
        $query->andFilterWhere(['assignment.item_name' => $this->role]);
        $query->andFilterWhere(['in', 'status', $this->status]);

        if (Yii::$app->request->get('orderBy', '')) {
            $direction = (int)Yii::$app->request->get('orderDirection');
            $query->orderBy([Yii::$app->request->get('orderBy') => $direction == 1 ? SORT_ASC : SORT_DESC]);
        } else {
            $query->orderBy(['users.id' => 1]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize', Yii::$app->cache->get(self::class . '_pageSize') ? Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ]
        ]);

        return $dataProvider;
    }
    
    public static function listAll($prompt = false)
    {
        $result = [];

        if($prompt)
            $result[0] = $prompt;

        $users = self::find()->all();

        foreach($users as $user)
            $result[$user->id] = $user->name;

        return $result;
    }
    
    public function is($role)
    {
        return is_array($this->role) ? in_array($role, $this->role) : $this->role == $role;
    }
    
    public function notifyAdmin()
    {
        $emailNotify = Setting::find()->where(['code' => Setting::EMAIL_MODERATOR])->one();

        if(isset($emailNotifyComments))
        {
            $email = Yii::$app->mailer->compose(['html' => 'notifyUserModerator-html'], [
                'email' => explode(',', $emailNotify->value),
                'model' => $this,
            ]);
            $email->setFrom(['post@' . $_SERVER['HTTP_HOST'] => 'TestSite']);
            $email->setSubject('Уведомление о новом пользователе');
            $email->setTo(explode(',', $emailNotify->value));

            $email->send();
        }
    }

    public function emailNotConfirmed()
    {
        $this->status = self::STATUS_EMAIL_NC;
        $this->generateActivationToken();
    }

    public function sendEmailConfirmation()
    {
        Yii::$app->mailer->compose(['html' => 'activationTokenEmail-html'], ['user' => $this])
            ->setFrom(['post@' . $_SERVER['HTTP_HOST'] => 'TestSite'])
            ->setTo($this->email)
            ->setSubject('Активация нового email адреса')
            ->send();
    }

    public function getUrl()
    {
        return Url::to(['/user/view', 'id' => $this->id]);
    }

    public function getRoleName()
    {
        if (!empty($this->roleName)) {
            return $this->roleName;
        }

        return self::$roles[ $this->role ];
    }
}
