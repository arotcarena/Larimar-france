<?php
namespace App\CustomException;

use App\Config\EnTextConfig;
use App\Config\TextConfig;
use Exception;

class AlreadyInCartException extends Exception
{
    public $message = TextConfig::ERROR_ALREADY_IN_CART;

    public $enMessage = EnTextConfig::ERROR_ALREADY_IN_CART;
}