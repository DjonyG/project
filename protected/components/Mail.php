<?php
/**
 * User: Hett
 * Date: 3/20/12
 * Time: 1:59 PM
 */

class Mail {

    static  function send($email, $subject, $message)
    {
        $status = false;
        $servers = Yii::app()->params['smtp'];
        shuffle($servers);
        foreach($servers as $server) {
            try {
                /** @var $mailer PHPMailer  */
                $mailer = Yii::createComponent('application.extensions.mailer.EMailer');
                $mailer->IsSMTP();
                $mailer->IsHTML();
                $mailer->From = Yii::app()->params['emailNoReply'];
                $mailer->FromName = 'Project';
                $mailer->CharSet = 'utf-8';
                $mailer->Subject = $subject;
                $mailer->Body = $message;
                $mailer->Timeout = 1;
                $mailer->AddAddress($email);
                $mailer->Host = $server['host'];
                $domains = Yii::app()->params['ourDomains'];
                $mailer->Hostname = current($domains);
                $status = $mailer->Send();
                if(!$status)
                    throw new Exception('Send mail return false on host ' . $server['host']);
                else
                    break;

            } catch (Exception $e) {

                Yii::log('Error send mail on host ' . $server['host'] . '  to ' . $email . '  '
                    . $e->getMessage(), CLogger::LEVEL_ERROR);
            }
        }

        if(YII_DEBUG) {
            Yii::log($message, CLogger::LEVEL_INFO, 'application.email');
        }

        return $status;
    }

}