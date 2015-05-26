<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $email
 * @property string $date_created
 * @property string $date_last
 * @property string $ip_create
 * @property string $ip_last
 * @property string $password
 * @property string $role
 * @property integer $email_is_verified
 * @property integer $banned
 * @property integer $security_lock
 * @property string $comment
 */
class User extends CActiveRecord
{

    const BAN_NONE = 0;
    const BAN_TYPE_FULL = 1;

    public static $banTypes = [
        self::BAN_NONE => null,
        self::BAN_TYPE_FULL => 'Full',
    ];

    public $new_password;


    public function beforeSave()
    {
        if (empty($this->ip_last)) $this->ip_last = null;
        if (empty($this->ip_create)) $this->ip_create = null;
        if (empty($this->email)) $this->email = null;
        if ($this->isNewRecord && !(Yii::app() instanceof CConsoleApplication)) {
            $ip = Yii::app()->request->userHostAddress;
            $this->ip_create = new CDbExpression("INET_ATON('{$ip}')");
            $this->ip_last = $this->ip_create;
        }

        //Смена пароля
        if (!empty($this->new_password)) {
            $this->password = sha1($this->new_password);
        }

        return parent::beforeSave();
    }

    /**
     * @inheritdoc
     */
    public function save($runValidation=true,$attributes=null)
    {
        if(!$runValidation || $this->validate($attributes))
            if($this->getIsNewRecord())
                return $this->insert($attributes);
            else {
                $attributes = $this->getAttributes();
                // no save attributes
                return $this->update(array_keys($attributes));
            }
        else
            return false;
    }


    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['email', 'required', 'on' => 'register'],
            ['email', 'length', 'max' => 50],
            ['email', 'application.models.validators.UserEmailValidator', 'userSelfId' => $this->id],
            ['comment', 'length', 'max' => 2048, 'on' => ['admin']],
            ['new_password', 'required', 'on' => 'register'],
            ['new_password', 'length', 'max' => 40, 'min' => 6],
            ['email_is_verified, banned, security_lock', 'numerical', 'integerOnly' => true, 'on' => ['admin']],
            ['id, email, date_created, date_last, ip_create, ip_last, password, role, email_is_verified, banned, security_lock, comment', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return [
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'email' => 'Email',
            'date_created' => 'Date Created',
            'date_last' => 'Date Last',
            'ip_create' => 'Ip Create',
            'ip_last' => 'Ip Last',
            'password' => 'Password',
            'role' => 'Role',
            'email_is_verified' => 'Email Is Verified',
            'banned' => 'Banned',
            'security_lock' => 'Security Lock',
            'comment' => 'Comment',
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('date_created', $this->date_created, true);
        $criteria->compare('date_last', $this->date_last, true);
        if ($this->ip_create) {
            $criteria->addCondition('ip_create = :ipc OR ip_last = :ipc');
            $criteria->params["ipc"] = ip2long($this->ip_create);
        }
        $criteria->compare('ip_last', ip2long($this->ip_last));
        $criteria->compare('password', $this->password, true);
        $criteria->compare('role', $this->role, true);
        $criteria->compare('email_is_verified', $this->email_is_verified);
        $criteria->compare('banned', $this->banned);
        $criteria->compare('security_lock', $this->security_lock);
        $criteria->compare('comment', $this->comment, true);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }

    public function login()
    {
        $ui = new UserIdentity($this->email, null);
        $ui->authenticate($this->id);
        if ($ui->errorCode === UserIdentity::ERROR_NONE) {
            Yii::app()->user->login($ui, Yii::app()->params['authRememberDuration']);
            return true;
        }
        return false;
    }

    public static function generatePassword($maxLength)
    {
        return substr(str_shuffle(str_repeat('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789', 5)), 0, $maxLength);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
