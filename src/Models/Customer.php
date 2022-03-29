<?php

namespace Wearepixel\YotpoPHP\Models;

/**
 * A Yotpo customer
 *
 * @property string $external_id
 * @property string $email
 * @property string $phone_number
 * @property string $first_name
 * @property string $last_name
 * @property string $gender - F/M/Other
 * @property string $account_created_at
 * @property string $account_status
 * @property string $tags
 * @property boolean $accepts_sms_marketing
 * @property boolean $accepts_email_marketing
 */
class Customer
{
    public $rawCustomer;

    public $external_id;
    public $email;
    public $phone_number;
    public $first_name;
    public $last_name;
    public $gender;
    public $account_created_at;
    public $account_status;
    public $tags;
    public $accepts_sms_marketing;
    public $accepts_email_marketing;

    public function __construct(array $customer)
    {
        $this->rawCustomer = $customer;

        foreach ($customer as $key => $data) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->$key = $data;
        }
    }
}
