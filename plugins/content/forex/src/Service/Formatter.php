<?php
/**
 * @package     plg_content_forex
 *
 * @copyright   Copyright (c) 2022 Nicholas K. Dionysopoulos. All legal rights reserved.
 * @license     GPL2
 */

namespace Joomla\Plugin\Content\ForEx\Service;

use Exception;
use Joomla\CMS\Application\CMSApplication;
use Joomla\CMS\Factory;
use NumberFormatter;

/**
 * A monetary value formatter service
 *
 * @since  1.0.0
 */
class Formatter
{
    /**
     * @since 1.0.3
     * @var   CMSApplication
     */
    private CMSApplication $app;

    /**
     * Constructor
     *
     * @param   CMSApplication  $app  The application we are running in
     *
     * @since   1.0.3
     */
    public function __construct(CMSApplication $app)
    {
        $this->app = $app;
    }


    /**
     * Formats a currency value
     *
     * @param   string       $currency  The currency of the monetary amount
     * @param   float        $amount    The amount of money to pretty print
     * @param   string|null  $locale    Optional. The locale to format for, e.g. el-GR, en-US, de-DE, etc.
     *
     * @return  string
     *
     * @throws  Exception
     * @since   1.0.0
     */
    public function currency(string $currency, float $amount, ?string $locale = null): string
    {
        $locale = $locale ?? $this->app->getLanguage()->getLocale()[0];

        // Prefer the PHP intl extension
        if (function_exists('numfmt_format_currency')) {
            $formatter = new NumberFormatter($locale, NumberFormatter::CURRENCY);

            return $formatter->formatCurrency($amount, $currency);
        }

        $currency = $this->currencySymbol($currency);

        return sprintf('%s %0.2f', $currency, $amount);
    }

    /**
     * Convert currency code to currency symbol.
     *
     * @see     https://www.xe.com/symbols.php
     * @see     https://www.ip2currency.com/currency-symbol
     *
     * @param   string  $currency  ISO currency code
     *
     * @return  string  Currency symbol, if supported; otherwise the currency code.
     * @since   1.0.0
     */
    private function currencySymbol(string $currency): string
    {
        switch ($currency) {
            case 'LEK':
                $currency = 'Lek';
                break;

            case 'AFN':
                $currency = '؋';
                break;

            case 'ARS':
            case 'AUD':
            case 'BSD':
            case 'BBD':
            case 'BMD':
            case 'BND':
            case 'CAD':
            case 'KYD':
            case 'CLP':
            case 'COP':
            case 'XCD':
            case 'SVC':
            case 'GYD':
            case 'HKD':
            case 'JEP':
            case 'LRD':
            case 'MXN':
            case 'NAD':
            case 'SGD':
            case 'NZD':
            case 'SBD':
            case 'SRD':
            case 'TVD':
            case 'USD':
                $currency = '$';
                break;

            case 'FJD':
                $currency = 'FJ$';
                break;

            case 'ANG':
            case 'AWG':
                $currency = 'ƒ';
                break;

            case 'AZN':
                $currency = '₼';
                break;

            case 'BYN':
                $currency = 'Br';
                break;

            case 'BZD':
                $currency = 'BZ$';
                break;

            case 'BOB':
                $currency = '$b';
                break;

            case 'BAM':
                $currency = 'KM';
                break;

            case 'BWP':
                $currency = 'P';
                break;

            case 'KZT':
            case 'BGN':
            case 'UZS':
            case 'KGS':
                $currency = 'лв';
                break;

            case 'BRL':
                $currency = 'R$';
                break;

            case 'KHR':
                $currency = '៛';
                break;

            case 'CNY':
            case 'JPY':
                $currency = '¥';
                break;

            case 'CRC':
                $currency = '₡';
                break;

            case 'HRK':
                $currency = 'kn';
                break;

            case 'PHP':
            case 'CUP':
                $currency = '₱';
                break;

            case 'CZK':
                $currency = 'Kč';
                break;

            case 'DKK':
            case 'NOK':
            case 'SEK':
            case 'ISK':
                $currency = 'kr';
                break;

            case 'DOP':
                $currency = 'RD$';
                break;

            case 'EGP':
            case 'FKP':
            case 'GIP':
            case 'GGP':
            case 'IMP':
            case 'SHP':
            case 'LBP':
            case 'GBP':
            case 'SYP':
                $currency = '£';
                break;

            case 'EUR':
                $currency = '€';
                break;

            case 'GHS':
                $currency = '¢';
                break;

            case 'GTQ':
                $currency = 'Q';
                break;

            case 'HNL':
                $currency = 'L';
                break;

            case 'HUF':
                $currency = 'Ft';
                break;

            case 'INR':
                $currency = '₹';
                break;

            case 'IDR':
                $currency = 'Rp';
                break;

            case 'OMR':
            case 'QAR':
            case 'SAR':
            case 'YER':
            case 'IRR':
                $currency = '﷼';
                break;

            case 'ILS':
                $currency = '₪';
                break;

            case 'JMD':
                $currency = 'J$';
                break;

            case 'KPW':
            case 'KRW':
                $currency = '₩';
                break;

            case 'LAK':
                $currency = '₭';
                break;

            case 'MKD':
                $currency = 'ден';
                break;

            case 'MYR':
                $currency = 'RM';
                break;

            case 'PKR':
            case 'SCR':
            case 'LKR':
            case 'MUR':
            case 'NPR':
                $currency = '₨';
                break;

            case 'MNT':
                $currency = '₮';
                break;

            case 'MZN':
                $currency = 'MT';
                break;

            case 'NIO':
                $currency = 'C$';
                break;

            case 'NGN':
                $currency = '₦';
                break;

            case 'PAB':
                $currency = 'B/.';
                break;

            case 'PYG':
                $currency = 'Gs';
                break;

            case 'PEN':
                $currency = 'S/.';
                break;

            case 'PLN':
                $currency = 'zł';
                break;

            case 'RON':
                $currency = 'lei';
                break;

            case 'RUB':
                $currency = '₽';
                break;

            case 'RSD':
                $currency = 'Дин.';
                break;

            case 'SOS':
                $currency = 'S';
                break;

            case 'ZAR':
                $currency = 'R';
                break;

            case 'TWD':
                $currency = '元';
                break;

            case 'THB':
                $currency = '฿';
                break;

            case 'TTD':
                $currency = 'TT$';
                break;

            case 'TRY':
                $currency = '₺';
                break;

            case 'UAH':
                $currency = '₴';
                break;

            case 'UYU':
                $currency = '$U';
                break;

            case 'VEF':
                $currency = 'Bs';
                break;

            case 'VND':
                $currency = '₫';
                break;

            case 'ZWD':
                $currency = 'Z$';
                break;

            case 'MAD':
                $currency = '.د.م';
                break;

            case 'MMK':
                $currency = 'K';
                break;

            case 'TND':
                $currency = 'DT';
                break;

            case 'XAF':
                $currency = 'FCFA';
                break;

            case 'XPF':
                $currency = 'F';
                break;

            case 'AED':
                $currency = 'د.إ';
                break;

            default:
                break;
        }

        return $currency;
    }
}