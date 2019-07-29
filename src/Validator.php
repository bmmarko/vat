<?php
declare(strict_types=1);

namespace Ibericode\Vat;

class Validator
{

    /**
     * Regular expression patterns per country code
     *
     * @var Patterns
     * @link http://ec.europa.eu/taxation_customs/vies/faq.html?locale=lt#item_11
     */
    private $patterns;
    /**
     * @var Vies\Client
     */
    private $client;

    /**
     * VatValidator constructor.
     *
     * @param Vies\Client $client        (optional)
     */
    public function __construct(Patterns $patterns = null, Vies\Client $client = null)
    {
        $this->patterns = $patterns ?: new Patterns();
        $this->client = $client ?: new Vies\Client();
    }

    /**
     * Checks whether the given string is a valid ISO-3166-1-alpha2 country code
     *
     * @param string $countryCode
     * @return bool
     */
    public function validateCountryCode(string $countryCode) : bool
    {
        $countries = new Countries();
        return isset($countries[$countryCode]);
    }

    /**
     * Checks whether the given string is a valid public IPv4 or IPv6 address
     *
     * @param string $ipAddress
     * @return bool
     */
    public function validateIpAddress(string $ipAddress) : bool
    {
        if ($ipAddress === '') {
            return false;
        }

        return (bool) filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE);
    }

    /**
     * Validate a VAT number format. This does not check whether the VAT number was really issued.
     *
     * @param string $vatNumber
     *
     * @return boolean
     */
    public function validateVatNumberFormat(string $vatNumber) : bool
    {
        if ($vatNumber === '') {
            return false;
        }

        $vatNumber = strtoupper($vatNumber);
        $country = substr($vatNumber, 0, 2);
        $number = substr($vatNumber, 2);

        // Country needs to be 2 letters ISO code
        if(strlen($country) !== 2){
            return false;
        }

        // 2nd part of VAT ID needs to be at least 1 char long
        if($number == false){
            return false;
        }

        $matches = $this->patterns->matches($country, $number);

        return $matches;
    }

    /**
     *
     * @param string $vatNumber
     *
     * @return boolean
     *
     * @throws Vies\ViesException
     */
    protected function validateVatNumberExistence(string $vatNumber) : bool
    {
        $vatNumber = strtoupper( $vatNumber);
        $country = substr($vatNumber, 0, 2);
        $number = substr($vatNumber, 2);
        return $this->client->checkVat($country, $number);
    }

    /**
     * Validates a VAT number using format + existence check.
     *
     * @param string $vatNumber Either the full VAT number (incl. country) or just the part after the country code.
     *
     * @return boolean
     *
     * @throws Vies\ViesException
     */
    public function validateVatNumber(string $vatNumber) : bool
    {
       return $this->validateVatNumberFormat($vatNumber) && $this->validateVatNumberExistence($vatNumber);
    }


}
