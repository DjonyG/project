<?php

return array(
    //############################################
    //                  ROLES
    //############################################
    'guest'=>array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Guest',
        'children'=>array(

        ),
        'bizRule' => null,
        'data' => null
    ),
    'registered' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Registered',
        'children' => array(
            'guest',
        ),
        'bizRule' => null,
        'data' => null
    ),
    'administrator' => array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'Administrator',
        'children' => array(
            'impersonate',
            'user',
        ),
        'bizRule' => null,
        'data' => null
    ),
    'system'=>array(
        'type' => CAuthItem::TYPE_ROLE,
        'description' => 'System RPC',
        'children' => array(

        ),
        'bizRule' => null,
        'data' => null,
    ),
    //############################################
    //                  OPERATIONS
    //############################################
    'impersonate'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Login in under other user',
        'children'=>array(),
        'bizRule'=>null,
        'data'=>null,
    ),
    'uploadFile'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Uploading file',
        'children'=>array(),
        'bizRule'=>null,
        'data'=>null,
    ),
    'updateFile'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Update file information',
        'children'=>array(),
        'data'=>null,
        'bizRule'=>'',
    ),
    'infoFile'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'View file information',
        'children'=>array(),
        'data'=>null,
        'bizRule'=>'',
    ),
    'updateProfile'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Update user profile',
        'children'=>array(),
        'data'=>null,
        'bizRule'=>'',
    ),
    'ftpUpload'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Getting ftp account for file uploading',
        'children'=>array(),
        'data'=>null,
        'bizRule'=>null,
    ),
    'viewPreview'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'View preview',
        'children'=>array(),
        'data'=>null,
        'bizRule'=>null,
    ),
    //############################################
    //                  TASKS
    //############################################
    'manageSpecifiedLanguage'=>array(
        'type'=>CAuthItem::TYPE_OPERATION,
        'description'=>'Manage specified language',
        'children'=>array(
            'manageLanguage',
        ),
        'data'=>null,
        'bizRule'=>'return UserSettings::get("allow_update_lang_".$params["language"], false);',
    ),
    'manageOwnUser'=>array(
        'type'=>CAuthItem::TYPE_TASK,
        'description'=>'Manage own user',
        'children'=>array(
            'manageUser',
        ),
        'data'=>null,
        'bizRule'=>'return $params["model"]->manager_id == Yii::app()->user->id;',
    ),
    'updateOwnFile'=>array(
        'type'=>CAuthItem::TYPE_TASK,
        'description'=>'Update own file information',
        'children'=>array('updateFile'),
        'data'=>null,
        'bizRule'=>'return $params["model"]->user_id == Yii::app()->user->id
                        && (!Yii::app()->user->isCoPartner() || Yii::app()->user->coPartner->full_file_access);',
    ),
    'infoOwnFile'=>array(
        'type'=>CAuthItem::TYPE_TASK,
        'description'=>'View own file information',
        'children'=>array('infoFile'),
        'data'=>null,
        'bizRule'=>'return $params["model"]->user_id == Yii::app()->user->id;',
    ),
    'updateGroupFile'=>array(
        'type'=>CAuthItem::TYPE_TASK,
        'description'=>'Update own group files',
        'children'=>array(''),
        'data'=>null,
        'bizRule'=>'return (!Yii::app()->user->isCoPartner() || Yii::app()->user->coPartner->full_file_access);'
    ),
    'updateOwnProfile'=>array(
        'type'=>CAuthItem::TYPE_TASK,
        'description'=>'Update user profile',
        'children'=>array('updateProfile'),
        'data'=>null,
        'bizRule'=>'return $params["model"]->user_id == Yii::app()->user->id;',
    ),
    'viewPreviewTestProgram'=>array(
        'type'=>CAuthItem::TYPE_TASK,
        'description'=>'Update user profile',
        'children'=>array('viewPreview'),
        'data'=>null,
        'bizRule'=>'return Yii::app()->user->profile && Yii::app()->user->profile->enable_test_program;',
    ),

);