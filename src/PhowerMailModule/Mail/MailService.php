<?php

namespace PhowerMailModule\Mail;

use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime;

class MailService
{

    const CONFIG_KEY = 'phower-mail';
    const MESSAGES_KEY = 'messages';
    const TRANSPORTS_KEY = 'transports';
    const DEFAULT_NAMESPACE = 'default';

    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $transports = [
        '\Zend\Mail\Transport\Sendmail' => 'sendmail',
        '\Zend\Mail\Transport\Smtp' => 'smtp',
    ];

    /**
     * Class constructor
     * 
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * Set config
     * 
     * @param array $config
     * @return \PhowerMailModule\Mail\MailService
     */
    public function setConfig(array $config)
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Get config
     * 
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Get transport
     * 
     * @param string $namespace
     * @return \PhowerMailModule\Mail\TransportInterface
     * @throws \InvalidArgumentException
     */
    public function getTransport($namespace = self::DEFAULT_NAMESPACE)
    {
        if (isset($this->config[self::TRANSPORTS_KEY][$namespace])) {
            $config = $this->config[self::TRANSPORTS_KEY][$namespace];

            if (!isset($config['type'])) {
                throw new \InvalidArgumentException('Undefined transport type for namespace: ' . $namespace);
            }

            $type = $config['type'];

            foreach ($this->transports as $class => $alias) {
                if ($type == trim($class, '\\') || strtolower($type) == $alias) {
                    $transport = new $class();
                    break;
                }
            }

            if (!isset($transport)) {
                throw new \InvalidArgumentException('Unable to create a transport of type: ' . $type);
            }

            if (isset($config['options'])) {
                if ($transport instanceof Smtp) {
                    $options = new SmtpOptions($config['options']);
                }
                if (isset($options)) {
                    $transport->setOptions($options);
                }
            }

            return $transport;
        }
    }

    /**
     * Get message
     * 
     * @param string $namespace
     * @return \PhowerMailModule\Mail\MessageInterface
     */
    public function getMessage($namespace = self::DEFAULT_NAMESPACE)
    {
        $message = new Message();

        if (isset($this->config[self::MESSAGES_KEY][$namespace])) {
            foreach ($this->config[self::MESSAGES_KEY][$namespace] as $property => $value) {
                $method = 'set' . str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $property)));
                if (method_exists($message, $method)) {
                    $params = is_array($value) ? $value : [$value];
                    call_user_func_array([$message, $method], $params);
                }
            }
        }

        if ($transport = $this->getTransport($namespace)) {
            $message->setTransport($transport);
        }

        return $message;
    }

}
