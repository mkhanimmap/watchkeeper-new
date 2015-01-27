<?php namespace Immap\Watchkeeper\Services\EmailSenders\Interfaces;

interface GenericSenderInterface {

    public function sendEmail($view, array $data = null, \Closure $callback = null);
}
