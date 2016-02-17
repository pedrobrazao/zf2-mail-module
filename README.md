ZF2 Mail Module
===============

Zend Framework 2 module to create and send email messages.

Instalation
-----------

### Composer ###

    composer require phower/zf2-mail-module

Configuration
-------------

1. Copy vendor/phower/zf2-mail-module/config/phower-mail-module.php.dist to 
   config/autoload/phower-mail-module.local.php and update your settings.

2. Edit config/application.php and add ```PhowerMailModule``` to your modules list.

Usage
-----

From a Controller or any Service Locator Aware instance:

    <?php
    $mailService = $this->getServiceLocator()->get('PhowerMailService');
    $message = $mailService->getMessage();
    $message->addTo('user@example.com')
            ->setSubject('Message subject')
            ->setBody('Message text.')
            ->send();

You're done!
