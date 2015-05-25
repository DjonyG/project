<?php

class ActivationController extends Controller {

    public function actionIndex($code)
    {
        /** @var ActivationCode $ac */
        $ac = ActivationCode::model()->findByPk($code);
        if(is_null($ac)) {
            throw new CHttpException(404, 'Указанный код активации не был найден');
        }

        switch ($ac->operation) {
            case ActivationCode::OPERATION_SET_EMAIL:
                $ac->user->email = $ac->data;
                $ac->user->email_is_verified = false;
                $ac->user->save();
                ActivationCode::model()->deleteAllByAttributes(array(
                    'user_id'=>$ac->user_id,
                    'operation'=>ActivationCode::OPERATION_SET_EMAIL,
                ));
                UserMessage::addFlash('Your email was successful changed');
                $this->redirect(array('/site/profile'));
                break;
            case ActivationCode::OPERATION_SET_PASSWORD:
                $ac->user->password = $ac->data;
                $ac->user->save(false);
                if(Yii::app()->user->id == $ac->user_id) {
                    Yii::app()->session->add('password_hash', $ac->user->password);
                }
                ActivationCode::model()->deleteAllByAttributes(array(
                    'user_id'=>$ac->user_id,
                    'operation'=>ActivationCode::OPERATION_SET_PASSWORD,
                ));
                UserMessage::addFlash('Your password was successful changed');
                $this->redirect(array('/site/profile'));
                break;
        }
    }

}