<?php
/**
 * Created by PhpStorm.
 * User: Евгений
 * Date: 26.05.2015
 * Time: 1:48
 */
class RegisterForm extends CFormModel
{
    public $email;
    public $password;
    public $acceptTerms = true;


    /**
     * Declares the validation rules.
     * The rules state that username and password are required,
     * and password needs to be authenticated.
     */
    public function rules()
    {
        return [
            ['email, password', 'required'],
            ['email', 'email'],
            ['email', 'length', 'max'=>255],
            ['email', 'uniqueValidator'],
            ['password', 'length', 'min'=>6],
            ['acceptTerms', 'validationAcceptTerms'],
        ];
    }

    public function uniqueValidator($attribute)
    {
        if(!$this->hasErrors() && User::model()->exists('email = :email', [':email'=>$this->$attribute])) {
            $this->addError($attribute, 'Пользователь с таким email-ом уже существует');
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
            'email'=>'E-mail',
            'password'=>'Пароль',
            'acceptTerms'=> 'Я согласен с <a href="' . Yii::app()->createUrl('/page/terms') . '">правилами использования сайта</a>',
        ];
    }

    public function createNewUser()
    {
        $user = new User();
        $user->email = $this->email;
        $user->new_password = $this->password;
        return $user->save()? $user->id : false;
    }

}
