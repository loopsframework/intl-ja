<?php

namespace Forms;

use Loops\Form\Annotated;
use Loops\PostalResolverJa;

/**
 * @FormElement(type=text,size=3,maxlength=3,name=zip1,label="郵便番号(1)",loops_intl_ja_postal=zip1)
 * @FormValidation(type=PresenceOf)
 * @FormValidation(type=RegEx,pattern="/^\d\d\d$/")
 * @FormElement(type=text,size=4,maxlength=4,name=zip2,label="郵便番号(2)",loops_intl_ja_postal=zip2)
 * @FormValidation(type=PresenceOf)
 * @FormValidation(type=RegEx,pattern="/^\d\d\d\d$/")
 * @FormElement(type=prefecture,name=prefecture,key=ja,display=ja,label="都道府県",loops_intl_ja_postal=prefecture)
 * @FormValidation(type=PresenceOf)
 * @FormElement(type=text,name=city,label="市区町村",loops_intl_ja_postal=city)
 * @FormValidation(type=PresenceOf)
 * @FormElement(type=text,name=district,label="町域",loops_intl_ja_postal=district)
 * @FormValidation(type=PresenceOf)
 * @FormElement(type=text,name=street,label="丁目・その他")
 * @FormElement(type=text,name=building,label="ビルディング")
 *
 * A form for a japanese address.
 *
 * This form utilizes the postal resolver class and will automatically fill in values prefecture/city/etc if a valid zip code
 * was entered.
 */
class JapaneseAddress extends Annotated {
    public $overwrite = TRUE;
    
    public function pageInit() {
        $this->resolver = new PostalResolverJa;
        return TRUE;
    }
    
    public function init($controller) {
        $this->elements['zip1']->setAttribute('loops_intl_ja_postal_resolverurl', $controller->url->getBaseURI().$this->resolver->pagepath);
        if($this->overwrite) {
            $this->elements['prefecture']->setAttribute('loops_intl_ja_postal_overwrite', TRUE);
            $this->elements['city']->setAttribute('loops_intl_ja_postal_overwrite', TRUE);
            $this->elements['district']->setAttribute('loops_intl_ja_postal_overwrite', TRUE);
        }
    }
}
