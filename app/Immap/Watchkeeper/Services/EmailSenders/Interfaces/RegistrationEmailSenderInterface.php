<?php namespace Immap\Watchkeeper\Services\EmailSenders\Interfaces;

interface RegistrationEmailSenderInterface extends GenericSenderInterface {

    public function sendWelcome($user, $password);
}
