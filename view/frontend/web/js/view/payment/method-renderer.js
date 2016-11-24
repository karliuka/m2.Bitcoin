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
