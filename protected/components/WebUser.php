<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Hett
 * Date: 17.07.12
 * Time: 12:38
 */

/**
 * @property string $role
 * @property User $profile
 * @property array $params
 * @property integer $isBanned
 * @property CAuthItem $authItem
 * @property string $trackPage;
 */
class WebUser extends CWebUser {

    protected $_profile;
    protected $_params;
    public $loginUrl=array('/site/login');
    public $trackPage;


    public function getProfile()
    {
        if(!$this->isGuest && is_null($this->_profile)) {
            $this->_profile = User::model()->findByPk($this->id);
        }
        return $this->_profile;
    }

    public function getRole()
    {
        if(!is_null($this->profile)) {
            return $this->profile->role;
        }
        return 'guest';
    }


//    public function setParam($name, $value)
//    {
//        UserSettings::set($name, $value, $this->id);
//    }
//
//    public function getParam($name, $default = null)
//    {
//        //Если имеется индивидуальный параметр, то отдаем его
//        if($this->id) {
//            $value = UserSettings::get($name, false, $this->id);
//            if($value !== false) {
//                return $value;
//            }
//        }
//        return isset($this->params[$name])? $this->params[$name] : $default;
//    }

    public function getAuthItem()
    {
        return Yii::app()->authManager->getAuthItem($this->getRole());
    }


    public function impersonate($user_id)
    {
        /** @var $user User */
        $user = User::model()->findByPk($user_id);
        if(is_null($user)) {
            throw new CHttpException(404, 'User not found!');
        }

        if(!$this->checkAccess('impersonate')) {
            throw new CHttpException(404, 'Only manager or admins has right access to impersonating.');
        }

        if(!$this->checkAccess('administrator') && !in_array($user->role, ['registered', 'partner'])) {
            throw new CHttpException(404, 'You can impersonate only under Registered ot Partner roles.');
        }

        if($this->role == 'manager' && $user->manager_id != $this->id) {
            throw new CHttpException(403, 'You can not enter that user');
        }

        if($this->id == $user_id) {
            UserMessage::addFlash('Already... It`s you', UserMessage::TYPE_WARNING);
            return false;
        }
        $ui = new UserIdentity($user->email, null);
        $ui->authenticate($user_id, false, true);
        $impersonate_from = $this->id;

        $this->login($ui, Yii::app()->session->get('duration', 0));
        Yii::app()->session->add('impersonate_from', $impersonate_from);
        Yii::app()->session->add('impersonate_last_page', Yii::app()->request->urlReferrer);

        return true;
    }

    public function unImpersonate()
    {
        $user_id = Yii::app()->session->get('impersonate_from');
        $last_page = Html::normalizeUrl(Yii::app()->session->get('impersonate_last_page'));
        Yii::app()->session->remove('impersonate_from');
        Yii::app()->session->remove('impersonate_last_page');

        /** @var $user User */
        $user = User::model()->findByPk($user_id);
        if(is_null($user)) {
            throw new CHttpException(404, 'User not found!');
        }
        $ui = new UserIdentity($user->email, null);
        if($ui->authenticate($user_id)) {
            $this->logout();
            $this->login($ui, Yii::app()->session->get('duration', 3600));
        }

        if(stripos($last_page, 'logout') === false
            && stripos($last_page, 'login') === false
            && stripos($last_page, 'impersonate') === false
        ){
            $this->returnUrl = $last_page;
        }
    }

    public function logout($destroySession=true)
    {
        $this->_profile = null;
        parent::logout($destroySession);
        $this->clearReturnUrl();
    }

    public function verify($id = null)
    {
        if(is_null($id)) {
            $ac = new ActivationCode();
            $ac->operation = ActivationCode::OPERATION_VERIFY_EMAIL;
            $ac->save();

            return Mail::send($this->profile->email, 'Account verification',
                Yii::app()->controller->renderPartial('//_messages/verify', array('code'=>$ac), true));
        } else {
            /** @var $model ActivationCode */
            $model = ActivationCode::model()->findByPk($id, 'operation = :op', array(':op'=>ActivationCode::OPERATION_VERIFY_EMAIL));
            if(!is_null($model)) {
                $model->user->email_is_verified = true;
                $model->user->save();
                ActivationCode::model()->deleteAllByAttributes(array(
                    'user_id'=>$model->user_id,
                    'operation'=>ActivationCode::OPERATION_VERIFY_EMAIL,
                ));
            }
            return $model;
        }
    }

    public function getIsBanned()
    {
        return $this->profile? $this->profile->banned : 0;
    }

    public function isImpersonate()
    {
        return (bool)Yii::app()->session->get('impersonate_from');
    }

    public function login($identity, $duration=0)
    {
        $res = parent::login($identity, $duration);
        if($res) {
            Yii::app()->session->add('duration', $duration);
        }
        return $res;
    }


    /**
     * Populates the current user object with the information obtained from cookie.
     * This method is used when automatic login ({@link allowAutoLogin}) is enabled.
     * The user identity information is recovered from cookie.
     * Sufficient security measures are used to prevent cookie data from being tampered.
     * @see saveToCookie
     */
    protected function restoreFromCookie()
    {
        $app=Yii::app();
        $request=$app->getRequest();
        $stateKeyPrefix = $this->getStateKeyPrefix();
        /** @var CHttpCookie $cookie */
        $cookie=$request->getCookies()->itemAt($stateKeyPrefix);
        if($cookie && !empty($cookie->value) && is_string($cookie->value) && ($data=$app->getSecurityManager()->validateData($cookie->value))!==false)
        {
            $data=@unserialize($data);
            if(is_array($data) && isset($data[0],$data[1],$data[2],$data[3], $data[4], $data[5]))
            {
                list($id,$name,$duration,$states,$ip, $password_hash)=$data;
                //check ip address
                $errors = [];
                if($ip != $app->request->userHostAddress) {
                    $errors[] = 'Try to restore cookie with different IP address: Cookie data' . print_r($data, true)
                        . ' ip address: ' . $app->request->userHostAddress;
                }

                /** @var User $user */
                $user = User::model()->findByPk($id);
                if(!$user) {
                    $errors[] = 'No exists user';
                } else {
                    if(md5($user->password) !== $password_hash) {
                        $errors[] = 'No valid password hash';
                    }
                }

                if(count($errors)) {
                    $cookie->expire = 0;
                    $cookie->value = '';
                    $request->cookies[$stateKeyPrefix] = $cookie;
//                    foreach($errors as $error)
//                        Yii::log($error, Logger::LEVEL_SEPARATED);

                    return;
                }

                if($this->beforeLogin($id,$states,true))
                {
                    $this->changeIdentity($id,$name,$states);
                    if($this->autoRenewCookie)
                    {
                        $this->saveToCookie($duration);
                    }
                    $this->afterLogin(true);
                }
            }
        }
    }

    /**
     * Renews the identity cookie.
     * This method will set the expiration time of the identity cookie to be the current time
     * plus the originally specified cookie duration.
     * @since 1.1.3
     */
    protected function renewCookie()
    {
        $request=Yii::app()->getRequest();
        $cookies=$request->getCookies();
        $cookie=$cookies->itemAt($this->getStateKeyPrefix());
        if($cookie && !empty($cookie->value) && ($data=Yii::app()->getSecurityManager()->validateData($cookie->value))!==false)
        {
            $data=@unserialize($data);
            if(is_array($data) && isset($data[0],$data[1],$data[2],$data[3], $data[4], $data[5]))
            {
                $this->saveToCookie($data[2]);
            }
        }
    }

    /**
     * Saves necessary user data into a cookie.
     * This method is used when automatic login ({@link allowAutoLogin}) is enabled.
     * This method saves user ID, username, other identity states and a validation key to cookie.
     * These information are used to do authentication next time when user visits the application.
     * @param integer $duration number of seconds that the user can remain in logged-in status. Defaults to 0, meaning login till the user closes the browser.
     * @see restoreFromCookie
     */
    protected function saveToCookie($duration)
    {
        $app=Yii::app();
        $cookie=$this->createIdentityCookie($this->getStateKeyPrefix());
        $cookie->expire=time()+$duration;
        $data=array(
            $this->getId(),
            $this->getName(),
            $duration,
            $this->saveIdentityStates(),
            $app->request->userHostAddress,
            $this->profile? md5($this->profile->password) : '',
        );
        $cookie->value=$app->getSecurityManager()->hashData(serialize($data));
        $app->getRequest()->getCookies()->add($cookie->name,$cookie);
    }

    /**
     * This method is called after the user is successfully logged in.
     * You may override this method to do some postprocessing (e.g. log the user
     * login IP and time; load the user permission information).
     * @param boolean $fromCookie whether the login is based on cookie.
     * @since 1.1.3
     */
    protected function afterLogin($fromCookie)
    {
        if(!is_null($this->profile)) {
            Yii::app()->session->add('password_hash', $this->profile->password);
        }
        Yii::app()->session->add('session_ip', Yii::app()->request->userHostAddress);

        parent::afterLogin($fromCookie);
    }


    public function getReturnUrl($defaultUrl=null)
    {
        if (isset(Yii::app()->request->cookies['__returnUrl'])) {
            return Yii::app()->request->cookies['__returnUrl']->value;
        } else {
            if($defaultUrl===null) {
                return  Yii::app()->getUrlManager()->showScriptName ?
                    Yii::app()->getRequest()->getScriptUrl() : Yii::app()->getRequest()->getBaseUrl().'/';
            } else {
                return CHtml::normalizeUrl($defaultUrl);
            }
        }
    }

    /**
     * Сохраняем returnUrl в куки, чтобы не занимать сессию
     */
    public function setReturnUrl($value)
    {
        $url = Html::normalizeUrl($value);
        if(strpos($url, 'logout') !== false)
            return;

        $cookie = new CHttpCookie('__returnUrl', $url);
        $cookie->httpOnly = true;
        $cookie->path = '/';
        Yii::app()->request->cookies['__returnUrl'] = $cookie;
    }

    /**
     * Удаляет значение, хранящееся в __returnUrl
     */
    public function clearReturnUrl()
    {
        unset(Yii::app()->request->cookies['__returnUrl']);
    }

}
