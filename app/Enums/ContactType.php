<?php
namespace App\Enums;

enum ContactType: string
{
    case SHIPPER = 'shipper';
    case RECEIVER = 'receiver';
    case OTHER = 'other';
    


    public static function list(ContactType $exclude)
    {
        return [];
        return array_filter(self::cases(), fn($s) => $s !== $exclude);        
    }



}
