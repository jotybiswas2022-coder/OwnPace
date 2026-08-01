<?php

namespace App\Services\Payments;

use InvalidArgumentException;

/**
 * PaymentGatewayManager — resolves a gateway adapter by provider key.
 * Gateways are registered once in a service provider; adding or removing a
 * gateway is a one-file change everywhere else.
 */
class PaymentGatewayManager
{
    /**
     * @var array<string, PaymentGatewayInterface>
     */
    private array $gateways;

    /**
     * @param  array<string, PaymentGatewayInterface>  $gateways
     */
    public function __construct(array $gateways)
    {
        $this->gateways = $gateways;
    }

    public function driver(string $name): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$name])) {
            throw new InvalidArgumentException("Unknown payment gateway: {$name}");
        }

        return $this->gateways[$name];
    }

    public function has(string $name): bool
    {
        return isset($this->gateways[$name]);
    }

    /**
     * @return array<string, PaymentGatewayInterface>
     */
    public function all(): array
    {
        return $this->gateways;
    }
}
