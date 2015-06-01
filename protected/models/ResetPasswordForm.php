<?php
/**
 * Created by PhpStorm.
 * User: Evgeny
 * Date: 29.10.2014
 * Time: 13:08
 */

class ResetPasswordForm extends CFormModel {

    public $code;
    public $new_password;
    public $new_password_confirm;

    public function rules()
    {
        return array(
            array('new_password, new_password_confirm, code', 'required'),
            array('code', 'length', 'min'=>40, 'max'=>40),
            array('new_password', 'length', 'min'=>6, 'max'=>40),
            array('new_password_confirm', 'compare', 'compareAttribute'=>'new_password'),
            array('code', 'validateCode'),
        );
    }

    public function attributeLabels()
    {
        return array(
            'code'=>'Secret code',
            'new_password'=>'Новый пароль',
            'new_password_confirm'=>'Подтвердите новый пароль',
        );
    }

    public function validateCode()
    {
        if(!$this->hasErrors() && !ActivationCode::model()->exists('id = :id', array(':id'=>$this->code))) {
            $this->addError('code', 'Неправильный код активации');
        }
    }

}