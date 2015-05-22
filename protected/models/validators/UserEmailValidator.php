<?php
/**
 * Created by PhpStorm.
 * User: Hett
 * Date: 10.09.14
 * Time: 11:52
 */


class UserEmailValidator extends CEmailValidator {

    public $userSelfId;

    /**
     * Validates a single attribute.
     * This method should be overridden by child classes.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     * @throws Exception
     */
    protected function validateAttribute($object, $attribute)
    {
        parent::validateAttribute($object, $attribute);

        $criteria = new CDbCriteria();
        $criteria->addColumnCondition([
            'email'=>$object->$attribute,
        ]);

        if(!empty($this->userSelfId)) {
            $criteria->addCondition('id != :id');
            $criteria->params['id'] = $this->userSelfId;
        }

        if(User::model()->exists($criteria)) {
            $this->addError($object, $attribute, 'E-Mail "'.Html::encode($object->$attribute) .'" already in use');
        }
    }
}