<?php

namespace PhowerMailModule\Service\Mail;

use PhowerMailModule\Mail\MailService;

interface MailServiceAwareInterface
{

    /**
     * Set mail service
     *
     * @param MailService $mailService
     * @return void
     */
    public function setMailService(MailService $mailService);

    /**
     * Get mail service
     * 
     * @return \PhowerMailModule\Mail\MailService
     */
    public function getMailService();
}
