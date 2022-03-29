<?php

namespace Wearepixel\YotpoPHP;

use Exception;
use Wearepixel\YotpoPHP\Models\Address;
use Wearepixel\YotpoPHP\Models\Customer;
use Wearepixel\YotpoPHP\Models\Order;
use Wearepixel\YotpoPHP\Support\Api;

/**
 * Base class for executing actions on the Yotpo API
*/
class YotpoPHP
{
    private ?Api $api = null;

    public function __construct(string $appKey, string $appSecret)
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;

        $this->api = new Api($appKey, $appSecret);
    }

    /**
     * Create a new customer in Yotpo
     */
    public function createCustomer(Customer $customer, Address $address)
    {
        $response = $this->api->makeRequest('PATCH', '/customers', [
            'external_id' => $customer->external_id,
            'email' => $customer->email,
            'phone_number' => $customer->phone_number,
            'first_name' => $customer->first_name,
            'last_name' => $customer->last_name,
            'gender' => $customer->gender,
            'account_created_at' => $customer->account_created_at,
            'account_status' => $customer->account_status,
            'tags' => $customer->tags,
            'address' => [
                'address1' => $address->address1,
                'address2' => $address->address2,
                'city' => $address->city,
                'company' => $address->company,
                'state' => $address->state,
                'zip' => $address->zip,
                'province_code' => $address->province_code,
                'country_code' => $address->country_code,
                'phone_number' => $address->phone_number,
            ],
            'accepts_sms_marketing' => $customer->accepts_sms_marketing,
            'accepts_email_marketing' => $customer->accepts_email_marketing,
        ]);

        $response = json_decode($response->getBody()->getContents());

        if ($response->yotpo_id) {
            return $response->yotpo_id;
        }

        return false;
    }

    /**
     * Verify a customer was created using the external id
     */
    public function verifyCustomerCreated(string $externalId)
    {
        $response = $this->api->makeRequest('GET', '/customers', [
            'external_ids' => $externalId,
        ]);

        $response = json_decode($response->getBody()->getContents());

        if (isset($response->customers[0]->email)) {
            return $response->customers[0];
        }

        return false;
    }

    /**
     * Create a new order in Yotpo
     */
    public function createOrder(Order $order)
    {
        $response = $this->api->makeRequest('POST', '/orders', [
            'external_id' => $order->external_id,
            'order_date' => $order->order_date,
            'line_items' => $order->line_items,
            'customer' => $order->customer,
        ]);

        $response = json_decode($response->getBody()->getContents());

        if (isset($response->yotpo_id)) {
            return $response->yotpo_id;
        }

        return false;
    }
}
