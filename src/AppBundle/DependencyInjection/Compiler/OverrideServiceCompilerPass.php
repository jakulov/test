<?php
/**
 * Created by PhpStorm.
 * User: yakov
 * Date: 13.10.2017
 * Time: 17:41
 */

namespace AppBundle\DependencyInjection\Compiler;

use AppBundle\Service\CommentManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class OverrideServiceCompilerPass
 * @package AppBundle\DependencyInjection\Compiler
 */
class OverrideServiceCompilerPass implements CompilerPassInterface
{
    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('fos_comment.manager.comment.default');
        $definition->setClass(CommentManager::class);
    }
}