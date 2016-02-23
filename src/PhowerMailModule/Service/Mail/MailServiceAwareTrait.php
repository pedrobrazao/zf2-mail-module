<?php

namespace PhowerMailModule\Service\Mail;

use PhowerMailModule\Mail\MailService;

trait MailServiceAwareTrait
{

    /**
     * @var \PhowerMailModule\Mail\MailService
     */
    protected $mailService;

    /**
     * Set mail service
     *
     * @param MailService $mailService
     * @return void
     */
    public function setMailService(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    /**
     * Get mail service
     * 
     * @return \PhowerMailModule\Mail\MailService
     */
    public function getMailService()
    {
        if ($this->mailService === null) {
            $this->mailService = $this->getServiceLocator()
                    ->get('PhowerMailService');
        }
        return $this->mailService;
    }

}
