<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\StatusTranslaterRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class StatusTranslaterExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('translate_status', [StatusTranslaterRuntime::class, 'translate']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('translate_status', [StatusTranslaterRuntime::class, 'translate']),
        ];
    }
}
