<?php
/**
 * AppExtension
 * PHP version 5.3
 *
 * @category DependencyInjection
 * @package  AppBundle\DependencyInjection
 * @author   Wanda Sipel
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://wierzba.wzks.uj.edu.pl/~12_puczko/aplikacja/app_dev.php/
 */

namespace AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
  *
 * @category DependencyInjection
 * @package  AppBundle\DependencyInjection
 * @author   Wanda Sipel
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     http://wierzba.wzks.uj.edu.pl/~12_puczko/aplikacja/app_dev.php/
 */
class AppExtension extends Extension
{
    /**
     * {@inheritdoc}
     * @param ArrayCollection  $configs   Config
     * @param ContainerBuilder $container Container
     *
     * @return mixed
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
