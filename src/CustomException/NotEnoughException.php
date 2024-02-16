<?php
namespace App\CustomException;

use App\Config\EnTextConfig;
use App\Config\TextConfig;
use Exception;

class NotEnoughException extends Exception
{
    public $message = TextConfig::ERROR_NOT_ENOUGH_QUANTITY;

    public $enMessage = EnTextConfig::ERROR_NOT_ENOUGH_QUANTITY;
}