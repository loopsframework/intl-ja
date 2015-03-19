if(jQuery) {
    jQuery(function() {
        var groups = {};
        
        jQuery.each(jQuery("[loops_intl_ja_postal]"), function(key, el) {
            var type = jQuery(el).attr('loops_intl_ja_postal');
            var name = jQuery(el).attr('name');
            var parts = name.split('-'); parts.pop();
            var stem = parts.join('-');
            if(!groups[stem]) groups[stem] = {};
            groups[stem][type] = jQuery(el);
        });
        
        jQuery.each(groups, function(stem, elements) {
            var resolverurl;
            
            function zip_changed(e) {
                var zip = elements.zip.val().replace('-', '');
                if(!zip.match(/^\d\d\d\d\d\d\d$/)) return;
                resolve(elements.zip.attr('loops_intl_ja_postal_resolverurl'), zip);
            }
            
            function zip12_changed(e) {
                var zip = elements.zip1.val() + elements.zip2.val();
                if(!zip.match(/^\d\d\d\d\d\d\d$/)) return;
                resolve(elements.zip1.attr('loops_intl_ja_postal_resolverurl'), zip);
            }
            
            function resolve(url, zip) {
                jQuery.getJSON(url + '/' + zip, function(data) {
                    if(data && data[0]) {
                        jQuery.each(data[0], function(key, value) {
                            if(!elements[key]) return;
                            if(elements[key].attr('loops_intl_ja_postal_button')) return;
                            if(elements[key].val() && !elements[key].is("[loops_intl_ja_postal_overwrite]")) return;
                            elements[key].val(value);
                        });
                    }
                });
            }
            
            if(elements.zip) {
                var buttonid = elements.zip.attr('loops_intl_ja_postal_button');
                
                if(buttonid) {
                    jQuery('#'+buttonid).on('click', zip_changed);
                }
                else {
                    elements.zip.on('input', zip_changed);
                }
            }
            
            if(elements.zip1 && elements.zip2) {
                var buttonid1 = elements.zip1.attr('loops_intl_ja_postal_button');
                var buttonid2 = elements.zip2.attr('loops_intl_ja_postal_button');
    
                if(buttonid1) {
                    jQuery('#'+buttonid).on('click', zip12_changed);
                }
                else {
                    elements.zip1.on('input', zip12_changed);
                }
                
                if(buttonid2) {
                    jQuery('#'+buttonid).on('click', zip12_changed);
                }
                else {
                    elements.zip2.on('input', zip12_changed);
                }
            }
        });
    });
}