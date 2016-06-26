<?php
/**
 * Configuration
 *
 * PHP version 5.3
 *
 * @category DependencyInjection
 * @package  AppBundle\DependencyInjection
 * @author   Wanda Sipel
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://wierzba.wzks.uj.edu.pl/~12_puczko/aplikacja/app_dev.php/
 */

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link
 * http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 *
 * @category DependencyInjection
 * @package  AppBundle\DependencyInjection
 * @author   Wanda Sipel
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://wierzba.wzks.uj.edu.pl/~12_puczko/aplikacja/app_dev.php/
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     * @return mixed
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('app');

        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.

        return $treeBuilder;
    }
}
