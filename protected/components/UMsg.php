<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 02.10.12
 * Time: 13:21
 */

class UMsg {
    public $message;
    public $type;
    public $is_closable;
    public $id;

    public function typeName()
    {
        return isset(UserMessage::$types[$this->type])? UserMessage::$types[$this->type] : UserMessage::$types[UserMessage::TYPE_SUCCESS];
    }

    public function getClass()
    {
        return isset(UserMessage::$classes[$this->type])? UserMessage::$classes[$this->type] : UserMessage::$classes[UserMessage::TYPE_SUCCESS];
    }

}