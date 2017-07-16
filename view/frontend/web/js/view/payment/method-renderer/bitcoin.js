/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * @License http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
define(
    [
        'Magento_Checkout/js/view/payment/default'
    ],
    function (Component) {
        'use strict';
        return Component.extend({
            defaults: {
                template: 'Faonni_Bitcoin/payment/bitcoin'
            }
        });
    }
); 
