<?php

namespace App\Twig\Runtime;

use App\Config\EnTextConfig;
use App\Config\TextConfig;
use Twig\Extension\RuntimeExtensionInterface;

class CivilityTranslaterRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function translate($frCivility)
    {
        if($frCivility === TextConfig::CIVILITY_M) 
        {
            return EnTextConfig::CIVILITY_M;
        } 
        if($frCivility === TextConfig::CIVILITY_F) 
        {
            return EnTextConfig::CIVILITY_F;
        }
        return $frCivility;
    }
}
