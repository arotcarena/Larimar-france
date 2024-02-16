<?php

namespace App\Twig\Runtime;

use App\Config\TextConfig;
use App\Config\EnTextConfig;
use Twig\Extension\RuntimeExtensionInterface;

class StatusTranslaterRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function translate($status)
    {
        return EnTextConfig::STATUS_LABELS[$status];
    }
}
