<?php namespace Immap\Watchkeeper\Services\EmailSenders;

use Immap\Watchkeeper\Services\EmailSenders\Interfaces\GenericSenderInterface as GenericSenderInterface;
use Illuminate\Mail\Mailer as Mailer;
use Illuminate\Config\Repository as Config;
use Closure as Closure;
abstract class AbstractEmailSender implements GenericSenderInterface
{
    protected $mailer;
    protected $config;
    public function __construct(Mailer $mailer,Config $config)
    {
        $this->mailer = $mailer;
        $this->config = $config;
    }

    public function sendEmail($view, array $data = null, Closure $callback = null)
    {
        return $this->mailer->send($view,$data, function($m) use ($callback,$data)
        {
            if (isset($data['from']) && ! isset($data['fromName'])) $m->from($data['from'],$data['from']);
            if (isset($data['from']) && isset($data['fromName'])) $m->from($data['from'],$data['fromName']);
            if (isset($data['to'])) $m->to($data['to']);
            if (isset($data['cc'])) $m->cc($data['cc']);
            if (isset($data['bcc'])) $m->bcc($data['bcc']);
            if ( ! is_null($callback)) call_user_func($callback, $m);
        });
    }
}
