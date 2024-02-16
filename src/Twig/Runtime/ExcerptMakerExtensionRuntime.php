<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class ExcerptMakerExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function excerpt(string $text, int $words = 20): string
    {
        $parts = explode(' ', $text);
        if(count($parts) > $words)
        {
            $newParts = array_splice($parts, 0, $words);
            return implode(' ', $newParts) . '...';
        }
        return $text;
    }
}
