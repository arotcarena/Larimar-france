<?php
namespace App\Helper;

use DateTime;

class PurchaseRefGenerator
{
    public function generate(): string 
    {
        $date = (new DateTime())->format('Ymd');
        return 'LF-' . $date . '-' . substr(str_shuffle(str_repeat('AZERTYUIOPQSDFGHJKLMWXCVBN0123456789', 5)), 0, 6);
    }
}