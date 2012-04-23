<?php

/*
 * This file is part of the KnpOAuthBundle package.
 *
 * (c) KnpLabs <hello@knplabs.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Knp\Bundle\OAuthBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class DoctrinePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('doctrine.orm.security.user.provider')) {
            $container
                ->register('knp_oauth.user.provider.entity')
                ->setClass($container->getParameter('knp_oauth.user.provider.entity.class'))
                ->setParent(new Reference('doctrine.orm.security.user.provider'))
                ;
        }
    }
}
