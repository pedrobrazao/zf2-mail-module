<?php

namespace PhowerMailModule\Mail;

use Zend\Mail;
use Zend\Mime;

class Message extends Mail\Message implements MessageInterface
{

    /**
     * @var \Zend\Mail\Transport\Mail\Transport\TransportInterface
     */
    protected $transport;

    /**
     * Set transport
     * 
     * @param Mail\Transport\TransportInterface $transport
     * @return \PhowerMailModule\Mail\Message
     */
    public function setTransport(Mail\Transport\TransportInterface $transport)
    {
        $this->transport = $transport;
        return $this;
    }

    /**
     * Get transport
     * 
     * @return \Zend\Mail\Transport\Mail\Transport\TransportInterface|null
     */
    public function getTransport()
    {
        return $this->transport;
    }

    /**
     * Set body with optional attachments
     * 
     * @param \Zend\Mime\Message|strin $body
     * @param array $attachments
     * @return type
     */
    public function setBody($body, $html = null, array $attachments = [])
    {
        if (!is_object($body) && (is_string($html) || $attachments)) {
            $parts = [];

            if (!is_null($body)) {
                $text = new Mime\Part($body);
                $text->type = Mime\Mime::TYPE_TEXT;
                $text->charset = $this->encoding ? : 'utf-8';
                $text->encoding = Mime\Mime::ENCODING_QUOTEDPRINTABLE;
                $parts[] = $text;
            }

            if (!is_null($html)) {
                $html = new Mime\Part($html);
                $html->type = Mime\Mime::TYPE_HTML;
                $html->charset = $this->encoding ? : 'utf-8';
                $html->encoding = Mime\Mime::ENCODING_QUOTEDPRINTABLE;
                $parts[] = $html;

                $type = 'multipart/alternative';

                if ($attachments) {
                    $content = new Mime\Message();
                    $content->setParts($parts);

                    $part = new Mime\Part($content->generateMessage());
                    $part->type = "multipart/alternative;\n boundary=\"" .
                            $content->getMime()->boundary() . '"';

                    $parts = [$part];

                    $type = 'multipart/related';
                }
            }

            foreach ($attachments as $path) {
                $resource = fopen($path, 'r');
                $attachment = new Mime\Part($resource);
                $attachment->type = mime_content_type($path);
                $attachment->filename = basename($path);
                $attachment->disposition = Mime\Mime::DISPOSITION_ATTACHMENT;
                $attachment->encoding = Mime\Mime::ENCODING_BASE64;
                $parts[] = $attachment;
            }

            $body = new Mime\Message();
            $body->setParts($parts);
        }

        parent::setBody($body);

        if (isset($type)) {
            $this->getHeaders()->get('content-type')->setType($type);
        }

        return $this;
    }

    /**
     * Send this message
     * 
     * @return 
     * @throws \RuntimeException
     */
    public function send()
    {
        if (!$this->isValid()) {
            throw new \RuntimeException('Invalid message; "From" property is missing.');
        }

        if ($this->transport === null) {
            throw new \RuntimeException('Undefined transport instance.');
        }

        return $this->transport->send($this);
    }

}
