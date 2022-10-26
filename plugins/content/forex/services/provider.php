<?php
/**
 * @package     plg_content_forex
 *
 * @copyright   Copyright (c) 2022 Nicholas K. Dionysopoulos. All legal rights reserved.
 * @license     GPL2
 */

use Joomla\CMS\Extension\PluginInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Event\DispatcherInterface;
use Joomla\Plugin\Content\ForEx\Extension\ForExPlugin;
use Joomla\Plugin\Content\ForEx\Provider\Factory as FactoryProvider;
use Joomla\Plugin\Content\ForEx\Service\Factory as FactoryService;

return new class implements ServiceProviderInterface {

    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $container->registerServiceProvider(new FactoryProvider());

                $params = PluginHelper::getPlugin('content', 'forex');
                $dispatcher = $container->get(DispatcherInterface::class);
                $plugin = new ForExPlugin($dispatcher, (array)$params);

                $plugin->setApplication(Factory::getApplication());
                $plugin->setFactory($container->get(FactoryService::class));

                return $plugin;
            }
        );
    }
};