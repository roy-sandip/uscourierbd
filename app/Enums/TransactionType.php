<?php
namespace App\Enums;

enum TransactionType: string
{
    case DEBIT = 'debit';
    case CREDIT = 'credit';
    


    public static function list(TransactionType $exclude): array
    {
        return [];
        return array_filter(self::cases(), fn($s) => $s !== $exclude);        
    }



}
