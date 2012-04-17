<?php

/*
 * This file is part of the KnpOAuthBundle package.
 *
 * (c) KnpLabs <hello@knplabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Knp\Bundle\OAuthBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\Config\FileLocator;

/**
 * KnpOAuthExtension
 *
 * @author Geoffrey Bachelet <geoffrey.bachelet@gmail.com>
 */
class KnpOAuthExtension extends Extension
{
    /**
     * @{inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/'));
        $loader->load('oauth.xml');

        if ($container->hasDefinition('doctrine.orm.security.user.provider')) {
            $container
                ->register('knp_oauth.user.provider.entity')
                ->setClass($container->getParameter('knp_oauth.user.provider.entity.class'))
                ->setParent(new Reference('doctrine.orm.security.user.provider'))
                ;
        }
    }
}
