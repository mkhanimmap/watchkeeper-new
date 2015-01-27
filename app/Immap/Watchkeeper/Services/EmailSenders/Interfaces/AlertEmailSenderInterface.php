<?php namespace Immap\Watchkeeper\Services\EmailSenders\Interfaces;

interface AlertEmailSenderInterface extends GenericSenderInterface {
    public function sendThreat($user, $threat);
}
