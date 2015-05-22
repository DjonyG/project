<?php
/**
 * User: Hett
 * Date: 25.02.12
 * Time: 18:15
 */

/**
 * Уведомления для пользователей.
 * Если нужно показать сообщения в этой же HTTP сессии, то достаточно использовать метод add()
 * Если есть необходимость показать в следующей сессии, то следует воспользоваться методов addFlash()
 */
class UserMessage {

    const TYPE_SUCCESS = 1;
    const TYPE_WARNING = 2;
    const TYPE_ATTENTION = 3;
    const TYPE_INFORMATION = 4;
    const TYPE_ERROR = 5;
    const TYPE_DANGER = 6;

    static $types = array(
        self::TYPE_SUCCESS => 'success',
        self::TYPE_WARNING => 'warning',
        self::TYPE_INFORMATION => 'information',
        self::TYPE_ATTENTION => 'attention',
        self::TYPE_ERROR => 'error',
        self::TYPE_DANGER => 'danger',
    );

    static $classes = array(
        self::TYPE_SUCCESS => 'success',
        self::TYPE_WARNING => 'warning',
        self::TYPE_ATTENTION => '',
        self::TYPE_INFORMATION => 'info',
        self::TYPE_ERROR => 'error',
        self::TYPE_DANGER => 'danger',
    );


    protected static $_messages = array();
    protected static $_permanent = null;

    public static function add($message, $type = self::TYPE_SUCCESS)
    {
        $msg = new UMsg();
        $msg->message = $message;
        $msg->type = $type;
        $msg->is_closable = false;
        self::$_messages[] = $msg;
    }

    public static function addFlash($message, $type = self::TYPE_SUCCESS)
    {
        $messages = Yii::app()->user->getFlash('messages');
        $msg = new UMsg();
        $msg->message = $message;
        $msg->type = $type;
        $msg->is_closable = false;
        $messages[] = $msg;
        Yii::app()->user->setFlash('messages', $messages);
    }

//    public static function addPermanent($message, $type = self::TYPE_SUCCESS, $user_id = null, $uid = null)
//    {
//        if(is_null($uid) || !Notification::model()->exists('uid = :uid AND user_id = :user_id', array(':uid'=>$uid, ':user_id'=>$user_id))) {
//            $model = new Notification();
//            $model->user_id = is_null($user_id)? Yii::app()->user->id : $user_id;
//            $model->type = $type;
//            $model->text = $message;
//            $model->uid = $uid;
//            if(!$model->save()) {
//                throw new CException(500, 'Failed save notification with message: ' . print_r($model->getErrors()));
//            }
//        }
//    }

    /**
     * @return UMsg[]
     */
    public static function getAll()
    {
        $flash = Yii::app()->user->hasFlash('messages')? Yii::app()->user->getFlash('messages') : array();
        $messages =  array_merge(self::$_messages, $flash);
//        self::readPermanentMessages();
//        if(is_array(self::$_permanent)) {
//            /** @var Notification $permanent */
//            foreach(self::$_permanent as $permanent) {
//                $model = new UMsg();
//                $model->message = $permanent->text;
//                $model->type = $permanent->type;
//                $model->is_closable = true;
//                $model->id = $permanent->id;
//                $messages[] = $model;
//            }
//        }
        self::$_messages = array();
        return $messages;
    }

    public static function has()
    {
        self::readPermanentMessages();
        return count(self::$_messages) || (is_array(self::$_permanent) && count(self::$_permanent)) || Yii::app()->user->hasFlash('messages');
    }

    protected static function readPermanentMessages()
    {
//        if(!Yii::app()->user->isGuest && is_null(self::$_permanent)) {
//            self::$_permanent = Notification::model()->findAllByAttributes(array('user_id'=>Yii::app()->user->id));
//        }
    }

}