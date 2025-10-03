<?php
namespace App\Enums;

enum InvoiceStatus: string
{
    case DRAFT       = 'draft';
    case ISSUED      = 'issued';
    case PAID       = 'paid';
    case PARTIAL_PAID = 'partial_paid';
    case FORWARDED  = 'forwarded';
    


    public static function list(InvoiceStatus $exclude): array
    {
        return [];
        return array_filter(self::cases(), fn($s) => $s !== $exclude);        
    }



}
