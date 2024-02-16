<?php
namespace App\CustomException;

use App\Config\EnTextConfig;
use App\Config\TextConfig;
use Exception;

class OverStockException extends Exception
{
    public $message = TextConfig::ERROR_NOT_ENOUGH_STOCK;

    public $enMessage = EnTextConfig::ERROR_NOT_ENOUGH_STOCK;
}