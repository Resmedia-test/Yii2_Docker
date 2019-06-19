<?php

namespace common\models;

use common\components\ImageAttachmentBehavior;
use frontend\components\MetaTagBehavior;
use himiklab\sitemap\behaviors\SitemapBehavior;
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $role
 * @property string $name
 * @property string $lastname
 * @property string $email
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $activation_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $last_login
 * @property integer $time_article
 * @property integer $count
 * @property integer $sum
 * @property string $password write-only password
 * @property string $rates
 * @property false|int birthday
 */
class User extends ActiveRecord implements IdentityInterface
{
    public $fullName;
    public $password;
    public $passwordRepeat;
    public $passwordOld;

    public $asum;
    public $acount;
    public $arate;

    public $role;
    public $termsOfUse;

    const SESSION_CLIENT = 'session_client';

    const ROLE_ADMIN = "admin";
    const ROLE_USER = "user";
    const ROLE_GUEST = "guest";

    const SCENARIO_SIGNUP = 'signup';
    const SCENARIO_SIGNUP_FAST = 'signup-fast';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_UPDATE_PASSWORD = 'update-password';
    const SCENARIO_LOGIN = 'login';
    const SCENARIO_RECOVERY = 'recovery';


    const STATUS_INACTIVE = 0;
    const STATUS_EMAIL_NC = 1;
    const STATUS_ACTIVE = 10;
    const STATUS_DELETED = 20;

    const GENDER_FEMALE = 0;
    const GENDER_MALE = 1;
    const GENDER_UNSET = 2;

    const PASSPORT_NOT_LOAD = 0;
    const PASSPORT_LOAD = 1;
    const PASSPORT_CHECK = 2;

    static $roles = [
        self::ROLE_GUEST => 'Гость',
        self::ROLE_USER => 'Пользователь',
        self::ROLE_ADMIN => 'Администратор',
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
                self::SCENARIO_SIGNUP => ['name', 'lastname', 'email', 'password', 'passwordRepeat'],
                self::SCENARIO_UPDATE => [
                    'name', 'lastname', 'email',
                    'phone', 'gender', 'birthday', 'about', 'experience', 'activation_token', 'status',
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
    public function rules()
    {
        return [
            [['name', 'role'], 'required'],

            ['email', 'email'],
            ['email', 'unique', 'message' => 'Этот email у нас уже есть!'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::$statuses)],

            [['password', 'password_hash', 'email', 'lastname', 'password_reset_token', 'activation_token', 'fullName', 'rates'], 'string'],
            ['role', 'in', 'range' => array_keys(self::$roles)],

            [
                ['password', 'passwordOld'], 'required',
                'on' => self::SCENARIO_UPDATE_PASSWORD
            ],

            //SCENARIO_SIGNUP_FAST
            [
                ['email', 'name'], 'required',
                'on' => self::SCENARIO_SIGNUP_FAST
            ],

            //SCENARIO_SIGNUP
            [
                ['email', 'name', 'lastname', 'password', 'passwordRepeat'], 'required',
                'on' => self::SCENARIO_SIGNUP
            ],
            [
                'passwordRepeat', 'compare', 'compareAttribute' => 'password',
                'message' => 'Пароли не совпадают',
                'on' => self::SCENARIO_SIGNUP
            ],

            [['id', 'status', 'name', 'lastname', 'last_login', 'role', 'phone', 'gender', 'birthday', 'about', 'acount', 'asum'], 'safe'],
        ];
    }

    public function attributes()
    {
        return array_merge(parent::attributes(), [
            'acount', 'asum',
        ]);
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
            'coverBehavior' => [
                'class' => ImageAttachmentBehavior::class,
                // type name for model
                'type' => 'user',
                // image dimmentions for preview in widget
                'previewHeight' => 250,
                'previewWidth' => 250,
                // extension for images saving
                'extension' => 'jpg',
                // path to location where to save images
                'directory' => Yii::getAlias('@frontend') . '/web/images/users',
                'url' => Yii::$app->params['domainFrontend'] . '/images/users',
                // additional image versions
                'versions' => [
                    'i200x200' => function ($img) {
                        $width = 200;
                        $height = 200;

                        return $img
                            ->copy()
                            ->thumbnail(new Box($width, $height), ImageInterface::THUMBNAIL_OUTBOUND);
                    },
                ]
            ],
            'sitemap' => [
                'class' => SitemapBehavior::class,
                'dataClosure' => function ($model) {
                    return [
                        'loc' => Url::to($model->getUrl(), true),
                        'lastmod' => time(),
                        'changefreq' => SitemapBehavior::CHANGEFREQ_DAILY,
                        'priority' => 0.8
                    ];
                }
            ],
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => false,
                'updatedAtAttribute' => false,
            ],
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
            'name' => 'Имя',
            'lastname' => 'Фамилия',
            'password' => 'Пароль',
            'passwordOld' => 'Старый пароль',
            'username' => 'Email',

            'phone' => 'Телефон',
            'gender' => 'Пол',
            'birthday' => 'День рождения',
            'about' => 'О себе',
        ];
    }

    public function getArticles()
    {
        return $this->hasMany(Article::class, ['user_id' => 'id']);
    }

    public function getArticlesCount()
    {
        return $this->getArticles()->having(['>', 'rate', 0])->orHaving(['>', 'rates', 0])->count();
    }

    public function getArticlesSum()
    {
        return $this->getArticles()->having(['>', 'rate', 0])->orHaving(['>', 'rates', 0])->sum('rate');
    }

    /**
     * @inheritdoc
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

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public static function isActivationTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
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
        $this->password_reset_token = ' ';
    }

    public function activate()
    {
        $this->activation_token = "";
        $this->status = self::STATUS_ACTIVE;
        $this->setPassword(Yii::$app->security->generateRandomString(10));
        $this->updateAttributes(['activation_token', 'status', 'password_hash']);
        $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

        \Yii::$app->mailer->compose(['html' => 'activationPassword-html'], ['user' => $this])
            ->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта'])
            ->setTo($this->email)
            ->setSubject('Пароль от профиля ' . $main_name->value ?: 'Название сайта')
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
        $this->setPassword(Yii::$app->security->generateRandomString(10));
        $this->updateAttributes(['password_reset_token', 'status', 'password_hash']);
        $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

        Yii::$app->mailer->compose(['html' => 'activationPassword-html'], ['user' => $this])
            ->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта'])
            ->setTo($this->email)
            ->setSubject('Пароль от профиля ' . $main_name->value ?: 'Название сайта')
            ->send();
    }

    public function getUserRole($id)
    {
        $user = AuthAssignment::findOne(['user_id' => $id]);
        return $user->item_name;
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
            //var_dump($this->role); die();
            $roles = Yii::$app->authManager->getRolesByUser($this->id);
            $role = reset($roles);

            if ($role && $role !== $this->role) {
                Yii::$app->authManager->revokeAll($this->id);
                $newRole = Yii::$app->authManager->getRole($this->role);
                Yii::$app->authManager->assign($newRole, $this->id);
            } else {
                $newRole = Yii::$app->authManager->getRole($this->role);

                if (isset($newRole)) {
                    Yii::$app->authManager->assign($newRole, $this->id);
                }
            }
        }

        //sending activation email
        if ($insert && (!empty($this->activation_token) && $this->status == self::STATUS_EMAIL_NC)) {
            //check if activation token exists and valid
            if (!self::isActivationTokenValid($this->activation_token)) {
                $this->generateActivationToken();
                $this->updateAttributes(['activation_token']);
            }

            $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
            //send activation email
            Yii::$app->mailer->compose(['html' => 'activationTokenEmail-html'], ['user' => $this])
                ->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта'])
                ->setTo($this->email)
                ->setSubject('Активация аккаунта на сайте ' . $main_name->value ?: 'Название сайта')
                ->send();
        }

        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeSave($insert)
    {
        if (!empty($this->password))
            $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);

        //setting activation key
        if ($insert) {
            $this->generateAuthKey();
            $this->generateActivationToken();
            $this->status = self::STATUS_INACTIVE;
            $this->rates = '';
            $this->last_login = time();
            $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);

            //send activation email
            Yii::$app->mailer->compose(['html' => 'activationToken-html'], ['user' => $this])
                ->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта'])
                ->setTo($this->email)
                ->setSubject('Активация аккаунта на сайте ' . $main_name->value ?: 'Название сайта')
                ->send();
        }

        return parent::beforeSave($this->isNewRecord);
    }

    public function search($params = null)
    {
        $query = User::find();

        $query->joinWith(['assignment']);

        $subQuery = Article::find()
            ->select('user_id, SUM(rate) as `asum`, COUNT(id) as `acount`, SUM(rate)/COUNT(id) as `arate`')
            ->andWhere(['or', ['>', 'rate', 0], ['>', 'rates', 0]])
            ->groupBy('user_id');
        $query->leftJoin(['articles' => $subQuery], 'articles.user_id = users.id');
        //$query->leftJoin(['articles']);

        if (isset($params)) {
            $this->load($params);
        }

        //adjust the query by adding the filters
        $query->filterWhere(['id' => $this->id]);
        $query->andFilterWhere(['like', 'name', $this->name]);
        $query->andFilterWhere(['like', 'lastname', $this->lastname]);
        $query->andFilterWhere(['like', 'email', $this->email]);
        $query->andFilterWhere(['last_login' => $this->last_login]);
        $query->andFilterWhere(['assignment.item_name' => $this->role]);
        $query->andFilterWhere(['in', 'status', $this->status]);

        //filtering by fullName
        $this->fullName = trim($this->fullName);

        if (!empty($this->fullName)) {
            $query->andFilterWhere([
                'or',
                ['like', 'UPPER(name)', explode(' ', mb_strtoupper($this->fullName))],
                ['like', 'UPPER(lastname)', explode(' ', mb_strtoupper($this->fullName))],
            ]);
        }

        if (Yii::$app->request->get('orderBy', '')) {
            $direction = (int)Yii::$app->request->get('orderDirection');
            $query->orderBy([Yii::$app->request->get('orderBy') => $direction == 1 ? SORT_ASC : SORT_DESC]);
        } else {
            $query->orderBy(['users.id' => -1]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => Yii::$app->request->get('pageSize',
                    Yii::$app->cache->get(self::class . '_pageSize') ?
                        Yii::$app->cache->get(self::class . '_pageSize') : 10),
            ]
        ]);

        return $dataProvider;
    }

    public function getFullName()
    {
        return $this->name . (!empty($this->lastname) ? ' ' . $this->lastname : '');
    }

    public static function listAll($prompt = false)
    {
        $result = [];

        if ($prompt)
            $result[0] = $prompt;

        $users = self::find()->all();

        foreach ($users as $user)
            $result[$user->id] = $user->getFullName();

        return $result;
    }

    public function getPreview($version = 'i200x200')
    {
        $result = '/img/no-img.jpg';

        if ($this->getBehavior('coverBehavior')->hasImage())
            $result = $this->getBehavior('coverBehavior')->getUrl($version);

        return $result;
    }

    public function is($role)
    {
        return is_array($this->role) ? in_array($role, $this->role) : $this->role == $role;
    }

    public function checkCookieComment()
    {
        $cookie = Yii::$app->request->cookies->get(Comment::COOKIE_GUEST_VAR);

        if (isset($cookie)) {
            $obj = base64_decode($cookie->value);
        }

        if (isset($obj)) {
            $comment = new Comment();
            $comment->text = $obj->text;
            $comment->model = $obj->model;
            $comment->model_id = $obj->model_id;
            $comment->reply_id = $obj->reply_id;
            $comment->user_id = Yii::$app->user->id;

            //setting allowed reply_id (only two levels)
            if (!empty($comment->reply_id)) {
                $reply = Comment::findOne($comment->reply_id);

                if (!empty($reply)) {
                    if ($reply->parent_id)
                        $comment->parent_id = $reply->parent_id;
                    else
                        $comment->parent_id = $reply->id;
                } else
                    $comment->reply_id = 0;
            }

            $comment->save();

            //Yii::$app->session->setFlash('success', 'Поздравляем! Ваш комментарий успешно опубликован.');
        }

        Yii::$app->response->cookies->remove(Comment::COOKIE_GUEST_VAR);
    }

    public function notifyAdmin()
    {
        $emailNotify = Setting::find()->where(['code' => Setting::EMAIL_MODERATOR])->one();

        if (isset($emailNotifyComments)) {
            $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
            $email = Yii::$app->mailer->compose(['html' => 'notifyUserModerator-html'], [
                'email' => explode(',', $emailNotify->value),
                'model' => $this,
            ]);
            $email->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта']);
            $email->setSubject('Уведомление о новом пользователе ' . $main_name->value ?: 'Название сайта');
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
        $main_name = Setting::findOne(['code' => 'site_name', 'status' => 1]);
        Yii::$app->mailer->compose(['html' => 'activationTokenEmail-html'], ['user' => $this])
            ->setFrom([Yii::$app->params['senderEmail'] => $main_name->value ?: 'Название сайта'])
            ->setTo($this->email)
            ->setSubject('Активация нового email адреса ' . $main_name->value ?: 'Название сайта')
            ->send();
    }

    public function getUrl()
    {
        return Url::to(['/user/view', 'id' => $this->id]);
    }
}
