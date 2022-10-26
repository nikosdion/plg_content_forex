<?php
/**
 * @package     Joomla\Plugin\Content\ForEx\Service
 * @subpackage
 *
 * @copyright   A copyright
 * @license     A "Slug" license name e.g. GPL2
 */

namespace Joomla\Plugin\Content\ForEx\Service;

use Joomla\CMS\Application\CMSApplication;
use Joomla\DI\Container;

/**
 * A service factory for our plugin
 *
 * @since  1.0.4
 */
class Factory
{
    /**
     * The CMS application we are running in
     *
     * @since 1.0.4
     * @var   CMSApplication
     */
    private CMSApplication $application;

    /**
     * The plugin's DI container
     *
     * @since 1.0.4
     * @var   Container
     */
    private Container $container;

    /**
     * Constructor
     *
     * @param   Container       $container    The plugin's DI container
     * @param   CMSApplication  $application  The CMS application we are running in
     *
     * @since   1.0.4
     */
    public function __construct(Container $container, CMSApplication $application)
    {
        $this->application = $application;
        $this->container   = $container;
    }

    /**
     * Returns a new instance of the ForEx service
     *
     * @return  ForEx
     *
     * @since   1.0.4
     */
    public function createForEx(): ForEx
    {
        return new ForEx($this->container);
    }

    /**
     * Returns a new instance of the Formatter service
     *
     * @return  Formatter
     *
     * @since   1.0.4
     */
    public function createFormatter(): Formatter
    {
        return new Formatter($this->application);
    }
}