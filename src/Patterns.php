<?php
declare(strict_types=1);

namespace Ibericode\Vat;

class Patterns
{

    /**
     * Regular expression patterns per country code
     *
     * @var array
     * @link http://ec.europa.eu/taxation_customs/vies/faq.html?locale=lt#item_11
     */
    protected $patterns = [
        'AT' => 'U[A-Z\d]{8}',
        'BE' => '(0\d{9}|\d{10})',
        'BG' => '\d{9,10}',
        'CY' => '\d{8}[A-Z]',
        'CZ' => '\d{8,10}',
        'DE' => '\d{9}',
        'DK' => '(\d{2} ?){3}\d{2}',
        'EE' => '\d{9}',
        'EL' => '\d{9}',
        'ES' => '[A-Z]\d{7}[A-Z]|\d{8}[A-Z]|[A-Z]\d{8}',
        'FI' => '\d{8}',
        'FR' => '([A-Z]{2}|\d{2})\d{9}',
        'GB' => '\d{9}|\d{12}|(GD|HA)\d{3}',
        'HR' => '\d{11}',
        'HU' => '\d{8}',
        'IE' => '[A-Z\d]{8}|[A-Z\d]{9}',
        'IT' => '\d{11}',
        'LT' => '(\d{9}|\d{12})',
        'LU' => '\d{8}',
        'LV' => '\d{11}',
        'MT' => '\d{8}',
        'NL' => '\d{9}B\d{2}',
        'PL' => '\d{10}',
        'PT' => '\d{9}',
        'RO' => '\d{2,10}',
        'SE' => '\d{12}',
        'SI' => '\d{8}',
        'SK' => '\d{10}',
        'NO' => '\d{9}(\S+)?',
        'CH' => '(E\d{9}(TVA|MWST|IVA)?|^\d{6})'
    ];


    /**
     * Checks whether the given string is a valid ISO-3166-1-alpha2 country code
     *
     * @param string $countryCode
     * @param string $number
     * @return bool
     */
    public function matches(string $countryCode, string $number) : bool
    {
        if(isset($this->patterns[$countryCode]) === false){
            return false;
        }

        $matches = preg_match('/^' . $this->patterns[$countryCode] . '$/', $number) > 0;
        return $matches;
    }

    /**
     * Sets pattern for country code
     *
     * @param string $countryCode
     * @param string $number
     * @return bool
     */

    public function setPattern(string $countryCode, string $pattern) : void
    {
        $this->patterns[$countryCode] = $pattern;
    }

}
