<?php
/**
 * @package     plg_content_forex
 *
 * @copyright   Copyright (c) 2022 Nicholas K. Dionysopoulos. All legal rights reserved.
 * @license     GPL2
 */

namespace Joomla\Plugin\Content\ForEx\Service;

use Joomla\CMS\Http\HttpFactory;
use SimpleXMLElement;
use Throwable;

/**
 * Foreign exchange conversion service.
 *
 * Converts a monetary value between different currencies using the latest ECB reference rates.
 *
 * @since  1.0.0
 */
class ForEx
{
    /**
     * The European Central Bank exchange rate data source
     *
     * @since 1.0.0
     * @var   string
     */
    private const RATE_SOURCE_URL = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    /**
     * Foreign exchange rates quotes against Euro, indexed by currency code
     *
     * @since 1.0.0
     * @var   array
     */
    protected static array $exchangeRates;

    /**
     * Public constructor
     *
     * @since  1.0.0
     */
    public function __construct()
    {
        // Initialise the exchange rates (if they have not already been initialised)
        self::$exchangeRates = self::$exchangeRates ?? $this->loadRates();
    }

    /**
     * Converts an amount of money between two currencies.
     *
     * If neither of the currencies is EUR it will convert from the source currency to EUR to the target currency. Loss
     * of precision is to be expected in this case.
     *
     * @param   string  $from   The currency to convert from
     * @param   string  $to     The currency to convert to
     * @param   float   $value  The amount of money to convert
     *
     * @return  float|null
     *
     * @since   1.0.0
     */
    public function convert(string $from, string $to, float $value): ?float
    {
        $from = strtoupper(trim($from));
        $to   = strtoupper(trim($to));

        if ($from === $to) {
            return $value;
        }

        if ($from === 'EUR') {
            return $this->fromEuro($to, $value);
        }

        if ($to === 'EUR') {
            return $this->toEuro($to, $value);
        }

        $euros = $this->toEuro($from, $value);

        if ($euros === null) {
            return null;
        }

        return $this->fromEuro($to, $value);
    }

    /**
     * Converts from EUR to a given currency
     *
     * @param   string  $currency  The currency to convert to
     * @param   float   $value     The amount of money to convert
     *
     * @return  float|null  NULL if we cannot convert to this currency
     *
     * @since   1.0.0
     */
    private function fromEuro(string $currency, float $value): ?float
    {
        $currency = strtoupper(trim($currency));

        if (!isset(self::$exchangeRates[$currency])) {
            return null;
        }

        return $value * self::$exchangeRates[$currency];
    }

    /**
     * Load the EUR-indexed forex exchange rates from the ECB
     *
     * @return  array
     *
     * @since   1.0.0
     */
    private function loadRates(): array
    {
        $response = HttpFactory::getHttp()
                               ->get(self::RATE_SOURCE_URL, [], 5);

        if ($response->code !== 200) {
            return [];
        }

        try {
            $xml = new SimpleXMLElement($response->body);
        } catch (Throwable $e) {
            return [];
        }

        $rates = [];

        foreach ($xml->Cube->Cube->Cube as $rate) {
            $rates[(string)$rate['currency']] = (float)$rate['rate'];
        }

        return $rates;
    }

    /**
     * Converts from a given currency to EUR
     *
     * @param   string  $currency  The currency to convert from
     * @param   float   $value     The amount of money to convert
     *
     * @return  float|null  NULL if we cannot convert from this currency
     *
     * @since   1.0.0
     */
    private function toEuro(string $currency, float $value): ?float
    {
        $currency = strtoupper(trim($currency));

        if (!isset(self::$exchangeRates[$currency])) {
            return null;
        }

        return $value / self::$exchangeRates[$currency];
    }

}