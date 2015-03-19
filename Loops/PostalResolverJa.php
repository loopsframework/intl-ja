<?php

namespace Loops;

use Loops\FilteredModelList;
use Phalcon\Forms\Element\Text;
use stdClass;

/**
 * Resolves japanese zip codes to address names
 *
 * JSON formatted data can be accessed via the following url:
 *     {$controller->url->getBasePath()}{$pagepath}/xxxxxxx
 * where xxxxxxx is a 7 digit japanese zip code.
 * The return format is typically an array with a single record that holds an object with
 * all available address data. In rare occasions, multiple records are returned if
 * the same zip code is used by multiple regions.
 * 
 * The following keys are available:
 *     hash:                a unique id for this record
 *     regioncode:          see link (1.)
 *     old_zip:             see link (2.)
 *     zip:                 see link (3.)
 *     zip1:                the first three numbers of 'zip'
 *     zip2:                the last four numbers of 'zip'
 *     prefecture:          prefecture name in japanese (kanji)
 *     prefecture_kana:     prefecture name in japanese (katakana)
 *     prefecutre_en:       prefecture name in english
 *     city:                city (市区町村) name in japanese (kanji)
 *     city_kana:           city (市区町村) name in japanese (katakana)
 *     city_en:             city (市区町村) name in english
 *     district:            district (町域) name in japanese, without values from brackets in the csv file (kanji)
 *     district_kana:       district (町域) name in japanese, without values from brackets in the csv file (katakana)
 *     district_en:         district (町域) name in english, without values from brackets in the csv file
 *     districtextra:       value in brackets that was in district from in the csv file
 *     districtextra_kana:  value in brackets that was in district_kana from in the csv file
 *     districtextra_en:    value in brackets that was in district_en from in the csv file
 *     more:                see link (10.)
 *     detailed:            see link (11.)
 *     block:               see link (12.)
 *     multiple:            see link (13.)
 *     renewed:             see link (14.)
 *     renewed_reason:      see link (15.)
 *
 * @link http://www.post.japanpost.jp/zipcode/dl/readme.html
 *
 * This class is essentially a preconfigured FilteredModelList and can also be used as such.
 */
class PostalResolverJa extends FilteredModelList {
    public function __construct() {
        parent::__construct('LoopsIntlJaPostal', ['zip'], -1, 'PostalResolverJa');
    }
    
    public function action($controller, $parameter) {
        if(count($parameter) == 1) {
            $this->filterform->confirm(['zip'=>$parameter[0]]);
            $this->filterform->confirmed = FALSE;
            $this->init($controller);
            $result = array();
            foreach($this->result->items as $zip) $result[] = $zip->toArray();
            return json_encode($result);
        }
    }
}