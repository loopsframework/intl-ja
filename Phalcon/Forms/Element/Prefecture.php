<?php

namespace Phalcon\Forms\Element;

use Loops;
use Phalcon\Validation\Validator\InclusionIn;

/**
 * A select form element with japanese prefectures preconfigured
 */
class Prefecture extends Select {
    private static $prefectures = array([ 'pref.code' => 'JP-01', 'ja' => '北海道', 'en' => 'Hokkaido', 'ja.kana' => 'ほっかいどう' ],
                                        [ 'pref.code' => 'JP-02', 'ja' => '青森県', 'en' => 'Aomori', 'ja.kana' => 'あおもりけん' ],
                                        [ 'pref.code' => 'JP-03', 'ja' => '岩手県', 'en' => 'Iwate', 'ja.kana' => 'いわてけん' ],
                                        [ 'pref.code' => 'JP-04', 'ja' => '宮城県', 'en' => 'Miyagi', 'ja.kana' => 'みやぎけん' ],
                                        [ 'pref.code' => 'JP-05', 'ja' => '秋田県', 'en' => 'Akita', 'ja.kana' => 'あきたけん' ],
                                        [ 'pref.code' => 'JP-06', 'ja' => '山形県', 'en' => 'Yamagata', 'ja.kana' => 'やまがたけん' ],
                                        [ 'pref.code' => 'JP-07', 'ja' => '福島県', 'en' => 'Fukushima', 'ja.kana' => 'ふくしまけん' ],
                                        [ 'pref.code' => 'JP-08', 'ja' => '茨城県', 'en' => 'Ibaraki', 'ja.kana' => 'いばらきけん' ],
                                        [ 'pref.code' => 'JP-09', 'ja' => '栃木県', 'en' => 'Tochigi', 'ja.kana' => 'とちぎけん' ],
                                        [ 'pref.code' => 'JP-10', 'ja' => '群馬県', 'en' => 'Gunma', 'ja.kana' => 'ぐんまけん' ],
                                        [ 'pref.code' => 'JP-11', 'ja' => '埼玉県', 'en' => 'Saitama', 'ja.kana' => 'さいたまけん' ],
                                        [ 'pref.code' => 'JP-12', 'ja' => '千葉県', 'en' => 'Chiba', 'ja.kana' => 'ちばけん' ],
                                        [ 'pref.code' => 'JP-13', 'ja' => '東京都', 'en' => 'Tokyo', 'ja.kana' => 'とうきょうと' ],
                                        [ 'pref.code' => 'JP-14', 'ja' => '神奈川県', 'en' => 'Kanagawa', 'ja.kana' => 'かながわけん' ],
                                        [ 'pref.code' => 'JP-15', 'ja' => '新潟県', 'en' => 'Niigata', 'ja.kana' => 'にいがたけん' ],
                                        [ 'pref.code' => 'JP-16', 'ja' => '富山県', 'en' => 'Toyama', 'ja.kana' => 'とやまけん' ],
                                        [ 'pref.code' => 'JP-17', 'ja' => '石川県', 'en' => 'Ishikawa', 'ja.kana' => 'いしかわけん' ],
                                        [ 'pref.code' => 'JP-18', 'ja' => '福井県', 'en' => 'Fukui', 'ja.kana' => 'ふくいけん' ],
                                        [ 'pref.code' => 'JP-19', 'ja' => '山梨県', 'en' => 'Yamanashi', 'ja.kana' => 'やまなしけん' ],
                                        [ 'pref.code' => 'JP-20', 'ja' => '長野県', 'en' => 'Nagano', 'ja.kana' => 'ながのけん' ],
                                        [ 'pref.code' => 'JP-21', 'ja' => '岐阜県', 'en' => 'Gifu', 'ja.kana' => 'ぎふけん' ],
                                        [ 'pref.code' => 'JP-22', 'ja' => '静岡県', 'en' => 'Shizuoka', 'ja.kana' => 'しずおかけん' ],
                                        [ 'pref.code' => 'JP-23', 'ja' => '愛知県', 'en' => 'Aichi', 'ja.kana' => 'あいちけん' ],
                                        [ 'pref.code' => 'JP-24', 'ja' => '三重県', 'en' => 'Mie', 'ja.kana' => 'みえけん' ],
                                        [ 'pref.code' => 'JP-25', 'ja' => '滋賀県', 'en' => 'Shiga', 'ja.kana' => 'しがけん' ],
                                        [ 'pref.code' => 'JP-26', 'ja' => '京都府', 'en' => 'Kyoto', 'ja.kana' => 'きょうとふ' ],
                                        [ 'pref.code' => 'JP-27', 'ja' => '大阪府', 'en' => 'Osaka', 'ja.kana' => 'おおさかふ' ],
                                        [ 'pref.code' => 'JP-28', 'ja' => '兵庫県', 'en' => 'Hyogo', 'ja.kana' => 'ひょうごけん' ],
                                        [ 'pref.code' => 'JP-29', 'ja' => '奈良県', 'en' => 'Nara', 'ja.kana' => 'ならけん' ],
                                        [ 'pref.code' => 'JP-30', 'ja' => '和歌山県', 'en' => 'Wakayama', 'ja.kana' => 'わかやまけん' ],
                                        [ 'pref.code' => 'JP-31', 'ja' => '鳥取県', 'en' => 'Tottori', 'ja.kana' => 'とっとりけん' ],
                                        [ 'pref.code' => 'JP-32', 'ja' => '島根県', 'en' => 'Shimane', 'ja.kana' => 'しまねけん' ],
                                        [ 'pref.code' => 'JP-33', 'ja' => '岡山県', 'en' => 'Okayama', 'ja.kana' => 'おかやまけん' ],
                                        [ 'pref.code' => 'JP-34', 'ja' => '広島県', 'en' => 'Hiroshima', 'ja.kana' => 'ひろしまけん' ],
                                        [ 'pref.code' => 'JP-35', 'ja' => '山口県', 'en' => 'Yamaguchi', 'ja.kana' => 'やまぐちけん' ],
                                        [ 'pref.code' => 'JP-36', 'ja' => '徳島県', 'en' => 'Tokushima', 'ja.kana' => 'とくしまけん' ],
                                        [ 'pref.code' => 'JP-37', 'ja' => '香川県', 'en' => 'Kagawa', 'ja.kana' => 'かがわけん' ],
                                        [ 'pref.code' => 'JP-38', 'ja' => '愛媛県', 'en' => 'Ehime', 'ja.kana' => 'えひめけん' ],
                                        [ 'pref.code' => 'JP-39', 'ja' => '高知県', 'en' => 'Kochi', 'ja.kana' => 'こうちけん' ],
                                        [ 'pref.code' => 'JP-40', 'ja' => '福岡県', 'en' => 'Fukuoka', 'ja.kana' => 'ふくおかけん' ],
                                        [ 'pref.code' => 'JP-41', 'ja' => '佐賀県', 'en' => 'Saga', 'ja.kana' => 'さがけん' ],
                                        [ 'pref.code' => 'JP-42', 'ja' => '長崎県', 'en' => 'Nagasaki', 'ja.kana' => 'ながさきけん' ],
                                        [ 'pref.code' => 'JP-43', 'ja' => '熊本県', 'en' => 'Kumamoto', 'ja.kana' => 'くまもとけん' ],
                                        [ 'pref.code' => 'JP-44', 'ja' => '大分県', 'en' => 'Oita', 'ja.kana' => 'おおいたけん' ],
                                        [ 'pref.code' => 'JP-45', 'ja' => '宮崎県', 'en' => 'Miyazaki', 'ja.kana' => 'みやざきけん' ],
                                        [ 'pref.code' => 'JP-46', 'ja' => '鹿児島県', 'en' => 'Kagoshima', 'ja.kana' => 'かごしまけん' ],
                                        [ 'pref.code' => 'JP-47', 'ja' => '沖縄県', 'en' => 'Okinawa', 'ja.kana' => 'おきなわ' ],
                                        [ 'pref.code' => 'JP-00', 'ja' => '海外', 'en' => 'Overseas', 'ja.kana' => 'かいがい' ] );
    
    /**
     * @param string $name The element name
     * @param array $attributes The attributes (see below)
     *
     * The following attributes can be specified:
     *
     * 'key':
     *    the types that will be used as the POST value (pref.code|ja|en|ja.kana)
     * 'display':
     *    the types that are going to be displayed as select options (pref.code|ja|en|ja.kana)
     *
     * If the above are not specified, ja or en will be chosen based on the current locale.
     * 
     * About the available types:
     *    pref.code: prefecture code as in 'ISO 3166-2:JP' (JP-00 is used for overseas)
     *    ja: prefecture names in japanese (kanji)
     *    ja.kana: prefecutre names in japanese (hiragana)
     *    en: prefecture name in english
     *
     * 'with_overseas':
     *    Another field that represents overseas is added to the options. (JP-00|海外|Overseas|かいがい)
     *
     * 'noValidation':
     *    Skip checking for valid values
     */
    public function __construct($name, $attributes = array()) {
        if(!array_key_exists('elements', $attributes)) {
            $options = array( '' => '' );
        }
        else {
            $options = $attributes['elements'];
            unset($attributes['elements']);
        }
        
        $key   = empty($attributes['key']    ) ? NULL : $attributes['key'];
        $value = empty($attributes['display']) ? NULL : $attributes['display'];
        
        foreach(self::$prefectures as $row) {
            if(empty($attributes['with_overseas']) && $row['pref.code'] == 'JP-00') continue;
            $options[self::resolve($row['pref.code'], $key)] = self::resolve($row['pref.code'], $value);
        }
        
        unset($attributes['key']);
        unset($attributes['display']);
        
        parent::__construct($name, $options, $attributes);
        
        if(empty($attributes['noValidation'])) {
            $this->addValidator(new InclusionIn(['domain' => array_keys($options)]));
        }
    }
    
    /**
     * Try to convert a prefecture representation to another one
     *
     * @param string $query A representation of a prefecture in any type
     * @param string|null $target The target type that should be retrieved or NULL for automatic selection based on locale
     * @return string|false The converted value or FALSE on error.
     */
    public static function resolve($query, $target = NULL) {
        if(!$target) {
            $locale = Loops::getlocale();
            
            foreach(self::$prefectures[0] as $key => $value) {
                if(substr($locale, 0, strlen($key)) == $key) {
                    $target = $key;
                    break;
                }
            }
            
            if(!$target) {
                $target = 'en';
            }
        }
        
        foreach(self::$prefectures as $row) {
            if(!in_array($query, $row)) continue;
            
            return $row[$target];
        }
        
        return FALSE;
    }
}