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
use Joomla\Plugin\Content\ForEx\Provider\ForEx;
use Joomla\Plugin\Content\ForEx\Provider\Formatter;
use Joomla\Plugin\Content\ForEx\Service\ForEx as ForExService;
use Joomla\Plugin\Content\ForEx\Service\Formatter as FormatterService;

return new class implements ServiceProviderInterface {

    public function register(Container $container)
    {
        $container->set(
            PluginInterface::class,
            function (Container $container) {
                $container->registerServiceProvider(new ForEx());
                $container->registerServiceProvider(new Formatter());

                $params = PluginHelper::getPlugin('content', 'forex');
                $dispatcher = $container->get(DispatcherInterface::class);
                $plugin = new ForExPlugin($dispatcher, (array)$params);

                $plugin->setApplication(Factory::getApplication());

                return $plugin;
            }
        );
    }
};