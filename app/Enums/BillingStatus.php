<?php
namespace App\Enums;

enum BillingStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case DUE = 'due';
    case INVOICED = 'invoiced';


    public static function list(BillingStatus $exclude): array
    {
        return [];
        return array_filter(self::cases(), fn($s) => $s !== $exclude);        
    }



}
