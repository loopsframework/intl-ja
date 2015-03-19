<?php

namespace Models;

use Loops\Model;

/**
 * Helper model for the PostalResolverJa class.
 */
class LoopsIntlJaPostal extends Model {
    /**
     * @PostalResolverJaCondition(operator='=',strict='1');
     */
    public $zip;
    
    public function initialize() {
        $this->setSource('loops_intl_ja_postal');
    }
}