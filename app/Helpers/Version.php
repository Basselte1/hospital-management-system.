<?php

namespace App\Helpers;

class Version
{
    /**
     * Get the full version string.
     * e.g. "2.1" or "2.1.4" (patch from git)
     */
    public static function get(): string
    {
        return config('app.version', '2.1');
    }

    /**
     * Get formatted version for display.
     * e.g. "v2.1"
     */
    public static function label(): string
    {
        return 'v' . static::get();
    }

    /**
     * Get full build string with environment badge.
     * e.g. "v2.1 · production"  or  "v2.1 · local [debug]"
     */
    public static function build(): string
    {
        $version = static::label();
        $env     = app()->environment();
        $debug   = config('app.debug') ? ' [debug]' : '';

        return "{$version} · {$env}{$debug}";
    }

    /**
     * Return version as array of parts.
     * e.g. ['major' => 2, 'minor' => 1, 'patch' => 0]
     */
    public static function parts(): array
    {
        $parts = explode('.', static::get());

        return [
            'major' => (int) ($parts[0] ?? 0),
            'minor' => (int) ($parts[1] ?? 0),
            'patch' => (int) ($parts[2] ?? 0),
        ];
    }
}