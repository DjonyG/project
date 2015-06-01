<?php

class ForgotPasswordForm extends CFormModel {

    public $email;

    public function rules()
    {
        return array(
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'checkEmailExist'],
        );
    }

    public function attributeLabels()
    {
        return array(
            'email'=>'E-mail',
        );
    }

    public function checkEmailExist($attribute)
    {
        if(!$this->hasErrors()) {
            if(!User::model()->exists('email = :email', array(':email'=>$this->email))) {
                $this->addError($attribute, 'Неправильный email!');
            }
        }
    }
}