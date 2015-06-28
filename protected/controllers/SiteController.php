<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return [
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>[
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			],
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>[
				'class'=>'CViewAction',
			],
		];
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

    public function actionImpersonate($id)
    {
        if(Yii::app()->user->impersonate($id)) {
            $this->redirect(Yii::app()->createUrl('/site/index'));
        } else {
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }


    /**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',['model'=>$model]);
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
        if (!Yii::app()->user->isGuest) {
            $this->redirect(['site/index']);
        }
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
        if(Yii::app()->request->isAjaxRequest)
            Yii::app()->clientScript->defaultScriptPosition = CClientScript::POS_END;

        $this->render('login', ['model'=>$model], false, true);

	}

    public function actionRegister()
    {
        if (!Yii::app()->user->isGuest) {
            $this->redirect(['site/index']);
        }
        $model = new RegisterForm;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'sign-up-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['RegisterForm'])) {
            $model->attributes = $_POST['RegisterForm'];
            // validate user input and redirect to the previous page if valid
            if ($model->validate()) {
                $userId = $model->createNewUser();
                if($userId === false) {
                    throw new CException('Не удалось сохранить пользователя');
                }
                $userIdentity = new UserIdentity($model->email, null);
                $userIdentity->authenticate($userId);
                Yii::app()->user->login($userIdentity);

                $this->redirect(Yii::app()->user->returnUrl);
            }
        }

        $this->render('register', ['model' => $model]);
    }

    public function actionProfile()
    {

        $model = new Profile;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'profile-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['Profile'])) {
            $model->attributes = $_POST['Profile'];
            /** @var $user User */
            $user = User::model()->findByAttributes(['id' => Yii::app()->user->id]);
            $model->user_id = $user->id;
            if ($model->validate()) {
                if ($model->save()) {
                    UserMessage::addFlash('Ваш профиль успешно создан');
                    $this->redirect(Yii::app()->user->returnUrl);
                } else {
                    throw new CException('Не удалось создать профиль');
                }
            }
        }
        $this->render('profile', ['model' => $model]);
    }

    public function actionForgotPassword()
    {

        $model = new ForgotPasswordForm;

        if (isset($_POST['ForgotPasswordForm'])) {
            $model->attributes = $_POST['ForgotPasswordForm'];
            if ($model->validate()) {
                /** @var $user User */
                $user = User::model()->findByAttributes(['email' => $model->email]);
                $code = new ActivationCode();
                $code->operation = ActivationCode::OPERATION_FORGOT_PASSWORD;
                $code->user_id = $user->id;
                if ($code->save()) {
                    if (Mail::send($user->email, 'Forgotten password', $this->renderPartial('//_messages/forgotPassword', ['code' => $code], true))) {
                        UserMessage::addFlash('Инструкция высланы на ваш E-mail');
                        $this->redirect(['/site/resetPassword']);
                    } else {
                        UserMessage::addFlash('Отправить сообщение не удалось', UserMessage::TYPE_WARNING);
                    }
                }
            }
        }
        $this->render('forgotPassword', ['model' => $model]);
    }

    public function actionResetPassword($code = null)
    {
        $model = new ResetPasswordForm;
        if (empty($model->code)) {
            $model->code = $code;
        }

        if (isset($_POST['ResetPasswordForm'])) {
            $model->attributes = $_POST['ResetPasswordForm'];
            if ($model->validate()) {
                //set password
                /** @var $code ActivationCode */
                $code = ActivationCode::model()->findByAttributes(['id' => $model->code]);
                if (!is_null($code)) {
                    $code->user->new_password = $model->new_password;
                    $code->user->save(false);
                    ActivationCode::model()->deleteAllByAttributes([
                        'user_id' => $code->user_id,
                        'operation' => ActivationCode::OPERATION_FORGOT_PASSWORD,
                    ]);

                    UserMessage::addFlash('Password was changed');
                    Yii::app()->user->returnUrl = ['site/index'];
                    $this->redirect(['site/index']);
                }
            }
        }
        $this->render('resetPassword', ['model' => $model]);
    }


    /**
     * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
    {
        if($id = Yii::app()->user->isImpersonate()) {
            Yii::app()->user->unImpersonate();
            $this->redirect(Yii::app()->user->returnUrl && Yii::app()->user->returnUrl != Yii::app()->request->url?
                Yii::app()->user->returnUrl : ['/site/index']);
        } else {
            Yii::app()->user->logout();
            $this->redirect(Yii::app()->homeUrl);
        }
    }
}