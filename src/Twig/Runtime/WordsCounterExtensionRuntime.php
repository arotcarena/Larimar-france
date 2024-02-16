<?php

namespace App\Twig\Runtime;

use Twig\Extension\RuntimeExtensionInterface;

class WordsCounterExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct()
    {
        // Inject dependencies if needed
    }

    public function doSomething(string $text)
    {
        return count(explode(' ', $text));
    }
}
