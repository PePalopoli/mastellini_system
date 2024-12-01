<?php

/*
 *  (c) Rogério Adriano da Silva <rogerioadris.silva@gmail.com>
 */

namespace Audi\AudiSystem\Provider;

use Silex\Application;
use Silex\Provider\SwiftmailerServiceProvider as BaseSwiftmailerServiceProvider;

/**
 * Class SwiftmailerServiceProvider.
 *
 * http://silex.sensiolabs.org/doc/providers/swiftmailer.html
 */
class SwiftmailerServiceProvider extends BaseSwiftmailerServiceProvider
{
    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        //
        parent::register($app);

        $app['swiftmailer.use_spool'] = false;
        $app['swiftmailer.options'] = array(
            'host' => 'smtp.gmail.com',
            'port' => '587',
            'username' => 'emails.audicomunicacao@gmail.com',
            'password' => 'wqdjzolozutwnlfw',
            'from' => 'contato@mastellini.com.br',
            'encryption' => 'tls',
            'auth_mode' => null,
        );
    }
}
