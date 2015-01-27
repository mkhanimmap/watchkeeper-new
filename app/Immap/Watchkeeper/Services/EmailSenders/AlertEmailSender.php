<?php namespace Immap\Watchkeeper\Services\EmailSenders;

use Immap\Watchkeeper\Services\EmailSenders\Interfaces\AlertEmailSenderInterface as AlertEmailSenderInterface;
use Illuminate\Config\Repository as Config;
use Illuminate\Mail\Mailer as Mailer;
class AlertEmailSender extends AbstractEmailSender implements AlertEmailSenderInterface {

    public function __construct(Mailer $mailer,Config $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;
    }

    public function sendThreat($user, $threat)
    {
        $email = $this->config->get('auth.register.admin-email');
        $emailname = $this->config->get('auth.register.admin-emailname','Watchkeeper');
        $view = 'emails.alert.threat'; //$this->config->get('auth.register.view-welcome');
        $subject = "Watchkeeper - Threat Event: ". $threat->title; //$this->config->get('auth.register.subject-welcome');
        $data['to'] = $user->email;
        $data['from'] = $email;
        $data['fromName'] = $emailname;
        $data['threat'] = $threat;
        $data['user'] = $user;
        return $this->sendEmail($view, $data, function($m) use ($subject) {
                $m->subject($subject);
        });
    }
}
