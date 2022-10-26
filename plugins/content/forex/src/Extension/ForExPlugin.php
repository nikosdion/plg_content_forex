<?php
/**
 * @package     plg_content_forex
 *
 * @copyright   Copyright (c) 2022 Nicholas K. Dionysopoulos. All legal rights reserved.
 * @license     GPL2
 */

namespace Joomla\Plugin\Content\ForEx\Extension;

use Exception;
use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\Event\Event;
use Joomla\Event\SubscriberInterface;
use Joomla\Plugin\Content\ForEx\Service\Factory;
use Joomla\Plugin\Content\ForEx\Service\ForEx;
use Joomla\Plugin\Content\ForEx\Service\Formatter;
use Joomla\String\StringHelper;

/**
 * A content plugin to convert monetary values between different currencies and format them according to the current
 * locale.
 *
 * @since  1.0.0
 */
class ForExPlugin extends CMSPlugin implements SubscriberInterface
{
    /**
     * The plugin's service factory
     *
     * @since 1.0.4
     * @var   Factory
     */
    private Factory $factory;

    /**
     * The ForEx conversion service
     *
     * @since 1.0.0
     * @var   ForEx
     */
    private ForEx $forex;

    /**
     * The monetary value formatter service
     *
     * @since 1.0.0
     * @var   Formatter
     */
    private Formatter $formatter;

    /**
     * Returns an array of events this subscriber will listen to.
     *
     * @return  array
     *
     * @since   1.0.0
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'onContentPrepare' => 'parseCurrencyConversions',
        ];
    }

    /**
     * Get the ForEx service from the provider
     *
     * @return  ForEx
     * @since   1.0.2
     */
    public function getForExService(): ForEx
    {
        $this->forex = $this->forex ?? $this->factory->createForEx();

        return $this->forex;
    }

    /**
     * Get the formatter service from the provider
     *
     * @return  Formatter
     * @since   1.0.2
     */
    public function getFormatterService(): Formatter
    {
        $this->formatter = $this->formatter ?? $this->factory->createFormatter();

        return $this->formatter;
    }

    /**
     * onContentPrepare event handler.
     *
     * Parses the text of the event's subject, replacing all {forex} plugin tags.
     *
     * @param   Event  $event  The event we are responding to
     *
     * @since   1.0.0
     */
    public function parseCurrencyConversions(Event $event): void
    {
        [$context, $row] = $event->getArguments();

        if (!is_object($row) || !isset($row->text) || !is_string($row->text) || empty($row->text)) {
            return;
        }

        if (StringHelper::strpos($row->text, 'forex') === false) {
            return;
        }

        $row->text = preg_replace_callback(
            '#{\s*forex(.*)\s*}(.*){\s*/forex\s*}#iU',
            [$this, 'processEveryInstance'],
            $row->text
        );
    }

    /**
     * Set the plugin's services factory
     *
     * @param   Factory  $factory  The plugin's services factory
     *
     * @since   1.0.4
     */
    public function setFactory(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Callback for preg_replace_callback.
     *
     * Parses an individual plugin tag's contents and returns the formatted, and possibly converted, monetary value.
     *
     * @param   array  $match
     *
     * @return  string
     *
     * @throws  Exception
     * @since   1.0.0
     */
    private function processEveryInstance(array $match): string
    {
        [$original, $arguments, $value] = $match;

        $arguments = explode(' ', str_replace("\t", '', strtoupper(trim($arguments))), 2);

        if (count($arguments) == 2) {
            [$from, $to] = $arguments;
            $newValue = $this->getForExService()->convert($from, $to, $value);
            $value    = $newValue ?? $value;
            $currency = ($newValue === null) ? $from : $to;
        } else {
            $currency = $arguments[0];
        }

        return $this->getFormatterService()->currency($currency, $value);
    }
}