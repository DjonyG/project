<?php

class ActivateCodeForm extends CFormModel {

    public $code;
    public $email;

    public function rules()
    {
        return [
            ['code', 'required'],
            ['email', 'required'],
            ['code', 'length', 'min'=>3, 'max'=>40],
            ['email', 'email'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'code'=>'Your code',
            'email'=>'Your email',
        ];
    }


}