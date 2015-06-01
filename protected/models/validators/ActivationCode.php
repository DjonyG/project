<?php

/**
 * This is the model class for table 'activation_code'.
 *
 * The followings are the available columns in table 'activation_code':
 * @property string $id
 * @property string $user_id
 * @property integer $operation
 * @property string $data
 * @property string $expire
 *
 * The followings are the available model relations:
 * @property User $user
 */
class ActivationCode extends CActiveRecord
{
    const OPERATION_VERIFY_EMAIL = 1;
    const OPERATION_FORGOT_PASSWORD = 2;
    const OPERATION_SET_EMAIL = 3;
    const OPERATION_SET_PASSWORD = 4;
    const OPERATION_SECURITY_LOCK = 5;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return ActivationCode the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'activation_code';
    }

    public function beforeSave()
    {
        if ($this->isNewRecord) {
            if (empty($this->id)) {
                $this->id = sha1(uniqid('', true));
            }
            if (empty($this->expire)) {
                $this->expire = new CDbExpression('NOW() + INTERVAL 1 DAY');
            }
            if (empty($this->user_id)) {
                $this->user_id = Yii::app()->user->id;
            }
        }

        return parent::beforeSave();
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['operation', 'numerical', 'integerOnly' => true],
            ['id', 'length', 'max' => 40],
            ['user_id', 'length', 'max' => 10],
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            ['id, user_id, operation, expire', 'safe', 'on' => 'search'],
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
            'user' => [self::BELONGS_TO, 'User', 'user_id'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('form', 'ID'),
            'user_id' => Yii::t('form', 'User'),
            'operation' => Yii::t('form', 'Operation'),
            'expire' => Yii::t('form', 'Expire'),
        ];
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('operation', $this->operation);
        $criteria->compare('expire', $this->expire);

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
        ]);
    }
}