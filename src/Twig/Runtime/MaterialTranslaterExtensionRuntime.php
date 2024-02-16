<?php

namespace App\Twig\Runtime;

use App\Config\TextConfig;
use App\Config\EnTextConfig;
use Twig\Extension\RuntimeExtensionInterface;

class MaterialTranslaterExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function toFr(string $material)
    {
        return TextConfig::PRODUCT_MATERIALS[$material];
    }
    public function toEn(string $material)
    {
        return EnTextConfig::PRODUCT_MATERIALS[$material];
    }
}
