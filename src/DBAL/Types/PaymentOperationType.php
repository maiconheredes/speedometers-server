<?php

namespace App\DBAL\Types;

use Fresh\DoctrineEnumBundle\DBAL\Types\AbstractEnumType;

final class PaymentOperationType extends AbstractEnumType
{
    public const PAYMENT_REVENUE = 'revenue';
    public const PAYMENT_EXPENSE = 'expense';

    protected static $choices = [
        self::PAYMENT_REVENUE => 'enum.payment.revenue',
        self::PAYMENT_EXPENSE => 'enum.payment.expense',
    ];
}
