<?php

namespace Loops\Filter;

/**
 * Helper class for hiragana/katakana conversion filters
 *
 * This class stores flags and an encoding for the mb_convert_kana function
 * which will be applied on filtering.
 */
abstract class ConvertKana {
    /**
     * @param string $flags Flags for mb_convert_kana
     * @param string $encoding Internal encoding. (Defaults to UTF-8)
     */
    public function __construct($flags, $encoding = 'UTF-8') {
        $this->encoding = $encoding;
        $this->flags = $flags;
    }
    
    /**
     * Applies the mb_convert_kana function with the previously set flags
     *
     * The mb_convert_kana function is applied two time, which enables half-width katakana to full-width
     * hiragana (or half-width hiragana to full-width katakana) conversions.
     *
     * @param string $input The filter input
     */
    public function filter($input) {
        return mb_convert_kana(mb_convert_kana($input, $this->flags, $this->encoding), $this->flags, $this->encoding);
    }
}
