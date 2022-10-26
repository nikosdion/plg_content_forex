<?php
/**
 * @package     Joomla\Plugin\Content\ForEx\Provider
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Plugin\Content\ForEx\Provider;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Plugin\Content\ForEx\Service\Formatter as FormatterService;

class Formatter implements ServiceProviderInterface
{
    /**
     * Registers the service provider with a DI container.
     *
     * @since  1.0.1
     */
    public function register(Container $container)
    {
        $container->set(
            FormatterService::class,
            function(Container $container)
            {
                return new FormatterService();
            }
        );
    }
}