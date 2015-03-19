<?php

namespace Loops\Filter;

/**
 * A filter that converts all types of katakana letters to full-width hiragana letters.
 */
class Hiragana extends ConvertKana {
    public function __construct($encoding = 'UTF-8') {
        parent::__construct('KHcV', $encoding);
    }
}