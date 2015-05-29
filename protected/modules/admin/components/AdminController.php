<?php

/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 19.10.12
 * Time: 12:51
 */
class AdminController extends Controller
{

    public $layout = '/layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return [
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            ['allow',  // allow all users to perform 'index' and 'view' actions
                'roles' => ['administrator'],
            ],
            ['deny', // allow authenticated user to perform 'create' and 'update' actions
                'users' => ['*'],
            ],
        ];
    }

}