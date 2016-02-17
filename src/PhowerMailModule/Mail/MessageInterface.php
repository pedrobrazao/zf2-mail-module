<?php

namespace PhowerMailModule\Mail;

use Zend\Mail\Transport\TransportInterface;

interface MessageInterface
{

    /**
     * Set transport
     * 
     * @param TransportInterface $transport
     */
    public function setTransport(TransportInterface $transport);

    /**
     * Get transport
     * 
     * @return TransportInterface|null
     */
    public function getTransport();

    /**
     * Send message
     * 
     * @return void
     */
    public function send();
}
