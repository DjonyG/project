<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    const ERROR_BANNED_FULL = 50;

    protected $_id;

    // Данный метод вызывается один раз при аутентификации пользователя.
    public function authenticate($id = false){

        if($id) {
            $model = User::model()->findByPk($id);
        } else {
            $model = User::model()->findByAttributes(array('email'=>$this->username));
        }

        if(is_null($model)) {
            $this->errorCode=self::ERROR_USERNAME_INVALID;
            $this->errorMessage = 'Неверный Email или Пароль';
        }else if ($model->password !== sha1($this->password) && $id === false) {
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
            $this->errorMessage = 'Неверный Email или Пароль';
        }else{
            if($model->banned == User::BAN_TYPE_FULL) {
                $this->errorCode = self::ERROR_BANNED_FULL;
                $this->errorMessage = 'Ваша учетная запись запрещена';
            }
            else {
                $this->_id = $model->id;
                $this->errorCode = self::ERROR_NONE;
            }
        }

        if (!is_null($model) && in_array($this->errorCode, [self::ERROR_NONE])
            && (long2ip($model->ip_last) != Yii::app()->request->userHostAddress || empty($model->date_last))
        )
            $model->updateLastInfo();

        return !$this->errorCode;
    }

    public function getId(){
        return $this->_id;
    }
}