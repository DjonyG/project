<?php

/**
 * This is the model class for table "profile".
 *
 * The followings are the available columns in table 'profile':
 * @property string $id
 * @property string $user_id
 * @property string $user_name
 * @property integer $floor
 * @property string $date_born
 * @property string $city
 * @property string $profile_foto
 * @property string $about
 *
 * The followings are the available model relations:
 * @property User $user
 */
class Profile extends CActiveRecord
{

    public $acceptTerms = true;

    const MAN = 1;
    const WOMAN = 2;

    public static $floorStatues = [
        self::MAN => 'Мужской',
        self::WOMAN => 'Женский',
    ];

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return [
            ['user_id, user_name, date_born, floor', 'required'],
            ['floor', 'numerical', 'integerOnly' => true],
            ['user_id', 'length', 'max' => 10],
            ['user_name, city, profile_foto', 'length', 'max' => 255],
            ['about', 'safe'],
            ['acceptTerms', 'validationAcceptTerms'],
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            ['id, user_id, user_name, floor, date_born, city, profile_foto, about', 'safe', 'on' => 'search'],
        ];
    }

    public function validationAcceptTerms($attribute)
    {
        if($this->$attribute != 1) {
            $this->addError($attribute, 'Я ознакомлен и согласен  <a href="'.Yii::app()->createUrl('page/terms').'" target="_blank">с правилами использования</a> данного сервиса и мне больше 18 лет.');
        }
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
			'id' => 'ID',
			'user_id' => 'User',
			'user_name' => 'Имя',
			'floor' => 'Пол',
			'date_born' => 'dd.mm.yyyy',
			'city' => 'Город',
			'profile_foto' => 'Profile Foto',
			'about' => 'About',
            'acceptTerms'=> 'Я ознакомлен и согласен  <a href="'.Yii::app()->createUrl('page/terms').'" target="_blank">с правилами использования</a> данного сервиса и мне больше 18 лет.',
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
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('floor',$this->floor);
		$criteria->compare('date_born',$this->date_born,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('profile_foto',$this->profile_foto,true);
		$criteria->compare('about',$this->about,true);

		return new CActiveDataProvider($this, [
			'criteria'=>$criteria,
		]);
	}

    public static function getCity($ip_address)
    {
        if(Yii::app()->request->userHostAddress == '127.0.0.1'){
            $ip_address = '109.171.22.55';
        }
        $url = 'http://ipgeobase.ru:7020/geo?ip=' . $ip_address;
        $xml = new DOMDocument();
        if ($xml->load($url)) {
            $root = $xml->documentElement;
            $result = [
                'country' => $root->getElementsByTagName('country')->item(0)->nodeValue,
                'region' => $root->getElementsByTagName('region')->item(0)->nodeValue,
                'city' => $root->getElementsByTagName('city')->item(0)->nodeValue,
                'district' => $root->getElementsByTagName('district')->item(0)->nodeValue];

            return $result['city'];
        } else {
            return 'Новосибирск';
        }

    }

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Profile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
