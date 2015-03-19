<?php

namespace Loops\Filter;

/**
 * A filter that converts all types of hiragana letters to full-witdh katakana letters.
 */
class Katakana extends ConvertKana {
    public function __construct($encoding = 'UTF-8') {
        parent::__construct('KHCV', $encoding);
    }
}
