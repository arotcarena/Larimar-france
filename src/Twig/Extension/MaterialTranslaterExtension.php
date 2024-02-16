<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\MaterialTranslaterExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MaterialTranslaterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('translate_material_toFr', [MaterialTranslaterExtensionRuntime::class, 'toFr']),
            new TwigFilter('translate_material_toEn', [MaterialTranslaterExtensionRuntime::class, 'toEn']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('translate_material_toFr', [MaterialTranslaterExtensionRuntime::class, 'toFr']),
            new TwigFunction('translate_material_toEn', [MaterialTranslaterExtensionRuntime::class, 'toEn']),
        ];
    }
}
