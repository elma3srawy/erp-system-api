<?php

namespace Modules\Sales\Tests\Feature\V1;

use Modules\Core\Models\Admin;
use Modules\CRM\Models\Customer;

trait Authentication
{
    protected function loginAsCustomer()
    {
        $customer = Customer::factory()->create();
        $this->actingAs($customer, 'customer_token');
        return $customer;
    }

    protected function loginAsAdmin()
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin, 'admin_token');
        return $admin;
    }
}
