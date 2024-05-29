<?php

namespace Skay1994\MyFramework\Enums;

use Skay1994\MyFramework\Facades\Container;

enum ConfigSourceEnum: string
{
    case APP = 'app';
    case FRAMEWORK = 'framework';

    public function path(string $file = null): string
    {
        $path = match ($this) {
            self::FRAMEWORK => Container::get('app')->defaultConfigPath(),
            default => Container::get('app')->configPath(),
        };

        return joinPaths($path, $file);
    }
}
