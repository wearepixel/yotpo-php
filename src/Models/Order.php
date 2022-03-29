<?php

namespace Wearepixel\YotpoPHP\Models;

/**
 * A Yotpo order
 *
 * @property string $external_id
 * @property string $order_date
 * @property array $line_items
 * @property Customer $customer
 */
class Order
{
    public $rawOrder;

    public $external_id;
    public $order_date;
    public $line_items = [];
    public $customer;

    public function __construct(array $order)
    {
        $this->rawOrder = $order;

        foreach ($order as $key => $data) {
            if (!property_exists($this, $key)) {
                continue;
            }

            $this->$key = $data;
        }

        return $this;
    }
}
