<?php

/**
 * app/Helpers/helpers.php
 *
 * Global PHP helper functions — direct PHP port of convert_chiffre_lettre.js
 *
 * ══════════════════════════════════════════════════════
 * INSTALLATION (one-time setup):
 *
 * 1. Save this file to:  app/Helpers/helpers.php
 *
 * 2. Add to composer.json under "autoload":
 *
 *      "autoload": {
 *          "psr-4": { "App\\": "app/" },
 *          "files": [
 *              "app/Helpers/helpers.php"
 *          ]
 *      }
 *
 * 3. Run:  composer dump-autoload
 * ══════════════════════════════════════════════════════
 */

if (!function_exists('NumberToLetter')) {

    /**
     * Convert a number to its French written form.
     * Direct PHP port of convert_chiffre_lettre.js — produces identical output.
     *
     * @param  int|float|string $nombre
     * @return string
     */
    function NumberToLetter($nombre): string
    {
        $letter = [
            0  => "zero",
            1  => "un",
            2  => "deux",
            3  => "trois",
            4  => "quatre",
            5  => "cinq",
            6  => "six",
            7  => "sept",
            8  => "huit",
            9  => "neuf",
            10 => "dix",
            11 => "onze",
            12 => "douze",
            13 => "treize",
            14 => "quatorze",
            15 => "quinze",
            16 => "seize",
            17 => "dix-sept",
            18 => "dix-huit",
            19 => "dix-neuf",
            20 => "vingt",
            30 => "trente",
            40 => "quarante",
            50 => "cinquante",
            60 => "soixante",
            70 => "soixante-dix",
            80 => "quatre-vingt",
            90 => "quatre-vingt-dix",
        ];

        $nombre = str_replace(' ', '', (string) $nombre);

        if (strlen($nombre) > 15) {
            return "depassement de capacite";
        }
        if (!is_numeric($nombre)) {
            return "Nombre non valide";
        }

        $nb = (float) $nombre;

        // Handle decimals: split on '.' and recurse (mirrors JS behaviour)
        if (ceil($nb) != $nb) {
            $parts = explode('.', $nombre);
            return NumberToLetter($parts[0]) . " virgule " . NumberToLetter($parts[1]);
        }

        $nb             = (int) $nb;
        $numberToLetter = '';
        $n              = strlen((string) $nb);

        switch (true) {

            // 1 digit
            case $n === 1:
                $numberToLetter = $letter[$nb];
                break;

            // 2 digits
            case $n === 2:
                if ($nb > 19) {
                    $quotient = (int) floor($nb / 10);
                    $reste    = $nb % 10;
                    if ($nb < 71 || ($nb > 79 && $nb < 91)) {
                        if ($reste === 0)      $numberToLetter = $letter[$quotient * 10];
                        elseif ($reste === 1)  $numberToLetter = $letter[$quotient * 10] . "-et-" . $letter[$reste];
                        else                   $numberToLetter = $letter[$quotient * 10] . "-" . $letter[$reste];
                    } else {
                        $numberToLetter = $letter[($quotient - 1) * 10] . "-" . $letter[10 + $reste];
                    }
                } else {
                    $numberToLetter = $letter[$nb];
                }
                break;

            // 3 digits
            case $n === 3:
                $quotient = (int) floor($nb / 100);
                $reste    = $nb % 100;
                if      ($quotient === 1 && $reste === 0) $numberToLetter = "cent";
                elseif  ($quotient === 1 && $reste !== 0) $numberToLetter = "cent " . NumberToLetter($reste);
                elseif  ($quotient > 1   && $reste === 0) $numberToLetter = $letter[$quotient] . " cents";
                else                                      $numberToLetter = $letter[$quotient] . " cent " . NumberToLetter($reste);
                break;

            // 4-6 digits (thousands)
            case $n >= 4 && $n <= 6:
                $quotient = (int) floor($nb / 1000);
                $reste    = $nb - $quotient * 1000;
                if      ($quotient === 1 && $reste === 0) $numberToLetter = "mille";
                elseif  ($quotient === 1 && $reste !== 0) $numberToLetter = "mille " . NumberToLetter($reste);
                elseif  ($quotient > 1   && $reste === 0) $numberToLetter = NumberToLetter($quotient) . " mille";
                else                                      $numberToLetter = NumberToLetter($quotient) . " mille " . NumberToLetter($reste);
                break;

            // 7-9 digits (millions)
            case $n >= 7 && $n <= 9:
                $quotient = (int) floor($nb / 1000000);
                $reste    = $nb % 1000000;
                if      ($quotient === 1 && $reste === 0) $numberToLetter = "un million";
                elseif  ($quotient === 1 && $reste !== 0) $numberToLetter = "un million " . NumberToLetter($reste);
                elseif  ($quotient > 1   && $reste === 0) $numberToLetter = NumberToLetter($quotient) . " millions";
                else                                      $numberToLetter = NumberToLetter($quotient) . " millions " . NumberToLetter($reste);
                break;

            // 10-12 digits (milliards)
            case $n >= 10 && $n <= 12:
                $quotient = (int) floor($nb / 1000000000);
                $reste    = $nb - $quotient * 1000000000;
                if      ($quotient === 1 && $reste === 0) $numberToLetter = "un milliard";
                elseif  ($quotient === 1 && $reste !== 0) $numberToLetter = "un milliard " . NumberToLetter($reste);
                elseif  ($quotient > 1   && $reste === 0) $numberToLetter = NumberToLetter($quotient) . " milliards";
                else                                      $numberToLetter = NumberToLetter($quotient) . " milliards " . NumberToLetter($reste);
                break;

            // 13-15 digits (billions)
            case $n >= 13 && $n <= 15:
                $quotient = (int) floor($nb / 1000000000000);
                $reste    = $nb - $quotient * 1000000000000;
                if      ($quotient === 1 && $reste === 0) $numberToLetter = "un billion";
                elseif  ($quotient === 1 && $reste !== 0) $numberToLetter = "un billion " . NumberToLetter($reste);
                elseif  ($quotient > 1   && $reste === 0) $numberToLetter = NumberToLetter($quotient) . " billions";
                else                                      $numberToLetter = NumberToLetter($quotient) . " billions " . NumberToLetter($reste);
                break;
        }

        // Mirror JS: add 's' when result ends with "quatre-vingt"
        if (substr($numberToLetter, -strlen("quatre-vingt")) === "quatre-vingt") {
            $numberToLetter .= "s";
        }

        return $numberToLetter;
    }
}







if (! function_exists('app_version')) {
    /**
     * Return the application version string.
     * Usage: app_version()        → "2.1"
     *        app_version('label') → "v2.1"
     *        app_version('build') → "v2.1 · production"
     */
    function app_version(string $format = 'raw'): string
    {
        return match($format) {
            'label' => \App\Helpers\Version::label(),
            'build' => \App\Helpers\Version::build(),
            default => \App\Helpers\Version::get(),
        };
    }
}