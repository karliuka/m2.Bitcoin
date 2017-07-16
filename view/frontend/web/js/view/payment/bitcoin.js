/**
 * Copyright © 2011-2017 Karliuka Vitalii(karliuka.vitalii@gmail.com)
 * @License http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */ 
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        rendererList.push(
            {
                type: 'faonni_bitcoin',
                component: 'Faonni_Bitcoin/js/view/payment/method-renderer/bitcoin'
            }
        );
        return Component.extend({});
    }
); 
