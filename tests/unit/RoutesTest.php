<?php

namespace PagarMe\Test;

use PagarMe\Routes;
use PHPUnit\Framework\TestCase;

class RoutesTest extends TestCase
{
    /**
     * @param mixed $subject
     */
    public static function assertIsCallable($subject, $message = ''): void
    {
        $type = gettype($subject);

        self::assertThat(
            is_callable($subject),
            self::isTrue(),
            "Failed asserting that subject of type $type can be called/invoked as a function/method."
        );
    }

    public function testOrderRoutes(): void
    {
        $routes = Routes::orders();
        
        $this->assertObjectHasAttribute('base', $routes);
        $this->assertIsCallable($routes->base);
        $this->assertObjectHasAttribute('details', $routes);
        $this->assertIsCallable($routes->details);
        $this->assertObjectHasAttribute('addCharge', $routes);
        $this->assertIsCallable($routes->addCharge);
        $this->assertobjecthasattribute('closed', $routes);
        $this->assertiscallable($routes->closed);
    }

    public function testOrderItemRoutes(): void
    {
        $routes = Routes::orderItems();

        $this->assertObjectHasAttribute('base', $routes);
        $this->assertIsCallable($routes->base);
        $this->assertObjectHasAttribute('details', $routes);
        $this->assertIsCallable($routes->details);
        $this->assertObjectHasAttribute('update', $routes);
        $this->assertIsCallable($routes->update);
        $this->assertObjectHasAttribute('delete', $routes);
        $this->assertIsCallable($routes->delete);
        $this->assertObjectHasAttribute('deleteAll', $routes);
        $this->assertIsCallable($routes->deleteAll);
    }

    public function testCustomerRoutes(): void
    {
        $routes = Routes::customers();

        $this->assertObjectHasAttribute('base', $routes);
        $this->assertIsCallable($routes->base);
        $this->assertObjectHasAttribute('details', $routes);
        $this->assertIsCallable($routes->details);
    }

    public function testAddressRoutes(): void
    {
        $routes = Routes::addresses();

        $this->assertObjectHasAttribute('base', $routes);
        $this->assertIsCallable($routes->base);
        $this->assertObjectHasAttribute('details', $routes);
        $this->assertIsCallable($routes->details);
        $this->assertObjectHasAttribute('delete', $routes);
        $this->assertIsCallable($routes->delete);
    }

    public function testCardRoutes(): void
    {
        $routes = Routes::cards();

        $this->assertObjectHasAttribute('base', $routes);
        $this->assertIsCallable($routes->base);
        $this->assertObjectHasAttribute('details', $routes);
        $this->assertIsCallable($routes->details);
        $this->assertObjectHasAttribute('delete', $routes);
        $this->assertIsCallable($routes->delete);
        $this->assertObjectHasAttribute('renew', $routes);
        $this->assertIsCallable($routes->renew);
    }

    public function testChargeRoutes(): void
    {
        $routes = Routes::charges();

        $this->assertObjectHasAttribute('base', $routes);
        $this->assertIsCallable($routes->base);
        $this->assertObjectHasAttribute('details', $routes);
        $this->assertIsCallable($routes->details);
        $this->assertObjectHasAttribute('capture', $routes);
        $this->assertIsCallable($routes->capture);
        $this->assertObjectHasAttribute('updateCard', $routes);
        $this->assertIsCallable($routes->updateCard);
        $this->assertObjectHasAttribute('updateBillingDue', $routes);
        $this->assertIsCallable($routes->updateBillingDue);
        $this->assertObjectHasAttribute('updatePaymentMethod', $routes);
        $this->assertIsCallable($routes->updatePaymentMethod);
        $this->assertObjectHasAttribute('holdCharge', $routes);
        $this->assertIsCallable($routes->holdCharge);
        $this->assertObjectHasAttribute('confirmCash', $routes);
        $this->assertIsCallable($routes->confirmCash);
        $this->assertObjectHasAttribute('cancel', $routes);
        $this->assertIsCallable($routes->cancel);
    }
}
