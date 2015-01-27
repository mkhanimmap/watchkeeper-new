<?php namespace Immap\Watchkeeper\Services\EmailSenders;

use Immap\Watchkeeper\Services\EmailSenders\Interfaces\RegistrationEmailSenderInterface as RegistrationEmailSenderInterface;
use Illuminate\Config\Repository as Config;
use Illuminate\Mail\Mailer as Mailer;
class RegistrationEmailSender extends AbstractEmailSender implements RegistrationEmailSenderInterface {

    public function __construct(Mailer $mailer,Config $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;
    }

    public function sendWelcome($user, $password)
    {
        $email = $this->config->get('auth.register.admin-email');
        $emailname = $this->config->get('auth.register.admin-emailname','Watchkeeper');
        $view = $this->config->get('auth.register.view-welcome');
        $subject = $this->config->get('auth.register.subject-welcome');
        $data['to'] = $user->email;
        $data['from'] = $email;
        $data['fromName'] = $emailname;
        $data['password'] = $password;
        $data['username'] = $user->username;
        $data['fullname'] = $user->firstname .' '. $user->lastname;
        return $this->sendEmail($view, $data, function($m) use ($subject) {
                $m->subject($subject);
        });
    }
}
