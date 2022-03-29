<?php

namespace Wearepixel\YotpoPHP\Models;

/**
 * A Yotpo customer
 *
 * @property string $address1
 * @property string $address2
 * @property string $city
 * @property string $company
 * @property string $state
 * @property string $zip
 * @property string $province_code
 * @property string $country_code
 * @property string $phone_number
 */
class Address
{
    public $rawAddress;

    public $address1;
    public $address2;
    public $city;
    public $company;
    public $state;
    public $zip;
    public $province_code;
    public $country_code;
    public $phone_number;

    public function __construct(array $address)
    {
        $this->rawAddress = $address;

        foreach ($address as $key => $data) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->$key = $data;
        }
    }
}
