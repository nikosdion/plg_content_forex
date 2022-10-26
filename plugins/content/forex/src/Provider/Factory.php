<?php
/**
 * @package     Joomla\Plugin\Content\ForEx\Provider
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Plugin\Content\ForEx\Provider;

use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory as JoomlaFactory;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Plugin\Content\ForEx\Service\Factory as FactoryService;

class Factory implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @since  1.0.1
     */
    public function register(Container $container)
    {
        $container->set(
            FactoryService::class,
            function(Container $container)
            {
                /** @var CMSApplication $app */
                $app = JoomlaFactory::getApplication();

                return new FactoryService($container, $app);
            }
        );
    }
}