<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 26.05.2015
 * Time: 1:48
 */
class RegisterForm extends CFormModel
{
    public $username;
    public $password;
    public $acceptTerms = true;

    private $identity;

    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            ['username, password', 'required'],
//            ['username', 'email'],
            ['username', 'length', 'max'=>255],
            ['username', 'uniqueValidator'],
            ['password', 'length', 'min'=>6],
            ['acceptTerms', 'validationAcceptTerms'],
        ];
    }

    public function uniqueValidator($attribute)
    {
        if(!$this->hasErrors() && User::model()->exists('username = :username', [':username'=>$this->$attribute])) {
            $this->addError($attribute, 'Пользователь с таким email-ом или телефоном уже существует');
        }
    }

    public function validationAcceptTerms($attribute)
    {
        if($this->$attribute != 1) {
            $this->addError($attribute, 'Вы должны согласиться с нашими  <a href="'.Yii::app()->createUrl('page/terms').'" target="_blank">условиями</a> , чтобы продолжить.');
        }
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'username'=>'E-mail или телефон',
            'password'=>'Пароль',
            'acceptTerms'=> 'Я согласен с <a href="' . Yii::app()->createUrl('/page/terms') . '">правилами использования сайта</a>',
        ];
    }

    public function createNewUser()
    {
        $user = new User();
        $user->username = $this->username;
        $user->new_password = $this->password;
        return $user->save()? $user->id : false;
    }

}
