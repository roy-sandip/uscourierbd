<?php
namespace App\Enums;

enum Currency: string
{
    case BDT = 'bdt';
    case USD = 'usd';
    

    public static function list(Currency $exclude)
    {
        return [];
        return array_filter(self::cases(), fn($s) => $s !== $exclude);        
    }



}
