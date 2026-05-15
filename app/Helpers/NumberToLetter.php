<?php

namespace App\Helpers;

class NumberToLetter
{
    /** @var array<int,string> */
    private static array $letter = [
        0  => 'zéro',
        1  => 'un',
        2  => 'deux',
        3  => 'trois',
        4  => 'quatre',
        5  => 'cinq',
        6  => 'six',
        7  => 'sept',
        8  => 'huit',
        9  => 'neuf',
        10 => 'dix',
        11 => 'onze',
        12 => 'douze',
        13 => 'treize',
        14 => 'quatorze',
        15 => 'quinze',
        16 => 'seize',
        17 => 'dix-sept',
        18 => 'dix-huit',
        19 => 'dix-neuf',
        20 => 'vingt',
        30 => 'trente',
        40 => 'quarante',
        50 => 'cinquante',
        60 => 'soixante',
        70 => 'soixante-dix',
        80 => 'quatre-vingt',
        90 => 'quatre-vingt-dix',
    ];

    /**
     * Convert a number to its French written-out form.
     *
     * @param  int|float|string $nombre
     * @return string
     */
    public static function convert($nombre): string
    {
        // Strip spaces
        $clean = str_replace(' ', '', (string) $nombre);

        if (strlen($clean) > 15) {
            return 'dépassement de capacité';
        }

        if (!is_numeric($clean)) {
            return 'Nombre non valide';
        }

        $nb = (float) $clean;

        // Handle decimals — recurse on each side of the decimal point
        if (floor($nb) != $nb) {
            $parts = explode('.', $clean);
            return self::convert($parts[0]) . ' virgule ' . self::convert($parts[1]);
        }

        $nb = (int) $nb;

        return self::numberToLetter($nb);
    }

    private static function numberToLetter(int $nb): string
    {
        $result = '';
        $n      = strlen((string) $nb);

        switch (true) {
            // 0–9
            case $n === 1:
                $result = self::$letter[$nb];
                break;

            // 10–99
            case $n === 2:
                if ($nb > 19) {
                    $quotient = (int) ($nb / 10);
                    $reste    = $nb % 10;

                    if ($nb < 71 || ($nb > 79 && $nb < 91)) {
                        if ($reste === 0) $result = self::$letter[$quotient * 10];
                        elseif ($reste === 1) $result = self::$letter[$quotient * 10] . '-et-' . self::$letter[$reste];
                        else $result = self::$letter[$quotient * 10] . '-' . self::$letter[$reste];
                    } else {
                        // 71-79 and 91-99: e.g. soixante-onze, quatre-vingt-onze
                        $result = self::$letter[($quotient - 1) * 10] . '-' . self::$letter[10 + $reste];
                    }
                } else {
                    $result = self::$letter[$nb];
                }
                break;

            // 100–999
            case $n === 3:
                $quotient = (int) ($nb / 100);
                $reste    = $nb % 100;

                if ($quotient === 1 && $reste === 0) $result = 'cent';
                elseif ($quotient === 1 && $reste !== 0) $result = 'cent ' . self::numberToLetter($reste);
                elseif ($quotient > 1 && $reste === 0) $result = self::$letter[$quotient] . ' cents';
                else $result = self::$letter[$quotient] . ' cent ' . self::numberToLetter($reste);
                break;

            // 1 000 – 999 999
            case $n >= 4 && $n <= 6:
                $quotient = (int) ($nb / 1000);
                $reste    = $nb - $quotient * 1000;

                if ($quotient === 1 && $reste === 0) $result = 'mille';
                elseif ($quotient === 1 && $reste !== 0) $result = 'mille ' . self::numberToLetter($reste);
                elseif ($quotient > 1 && $reste === 0) $result = self::numberToLetter($quotient) . ' mille';
                else $result = self::numberToLetter($quotient) . ' mille ' . self::numberToLetter($reste);
                break;

            // 1 000 000 – 999 999 999
            case $n >= 7 && $n <= 9:
                $quotient = (int) ($nb / 1_000_000);
                $reste    = $nb % 1_000_000;

                if ($quotient === 1 && $reste === 0) $result = 'un million';
                elseif ($quotient === 1 && $reste !== 0) $result = 'un million ' . self::numberToLetter($reste);
                elseif ($quotient > 1 && $reste === 0) $result = self::numberToLetter($quotient) . ' millions';
                else $result = self::numberToLetter($quotient) . ' millions ' . self::numberToLetter($reste);
                break;

            // 1 000 000 000 – 999 999 999 999
            case $n >= 10 && $n <= 12:
                $quotient = (int) ($nb / 1_000_000_000);
                $reste    = $nb - $quotient * 1_000_000_000;

                if ($quotient === 1 && $reste === 0) $result = 'un milliard';
                elseif ($quotient === 1 && $reste !== 0) $result = 'un milliard ' . self::numberToLetter($reste);
                elseif ($quotient > 1 && $reste === 0) $result = self::numberToLetter($quotient) . ' milliards';
                else $result = self::numberToLetter($quotient) . ' milliards ' . self::numberToLetter($reste);
                break;

            // 1 000 000 000 000 – 999 999 999 999 999
            case $n >= 13 && $n <= 15:
                $quotient = (int) ($nb / 1_000_000_000_000);
                $reste    = $nb - $quotient * 1_000_000_000_000;

                if ($quotient === 1 && $reste === 0) $result = 'un billion';
                elseif ($quotient === 1 && $reste !== 0) $result = 'un billion ' . self::numberToLetter($reste);
                elseif ($quotient > 1 && $reste === 0) $result = self::numberToLetter($quotient) . ' billions';
                else $result = self::numberToLetter($quotient) . ' billions ' . self::numberToLetter($reste);
                break;
        }

        // French grammar rule: "quatre-vingts" takes a plural 's' only when final
        if (str_ends_with($result, 'quatre-vingt')) {
            $result .= 's';
        }

        return $result;
    }
}