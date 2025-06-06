/**
 * Giodc Woo Swiper JavaScript
 */
(function($) {
    'use strict';

    /**
     * Initialize all Swiper instances on the page
     */
    function initSwipers() {
        // Find all Swiper containers with the giodc-swiper class prefix
        $('.swiper[class*="giodc-swiper-"]').each(function() {
            var $container = $(this);
            var containerId = $container.attr('class').split(' ')
                .find(className => className.startsWith('giodc-swiper-'));
            
            if (!containerId) return;
            
            // Get responsive settings from data attributes
            var mobileColumns = parseInt($container.data('mobile-columns') || 2);
            var tabletColumns = parseInt($container.data('tablet-columns') || 4);
            var desktopColumns = parseInt($container.data('desktop-columns') || 5);
            
            // Initialize this Swiper instance
            new Swiper('.' + containerId, {
                slidesPerView: mobileColumns, // Mobile default
                spaceBetween: 20,
                pagination: {
                    el: '.' + containerId + ' .swiper-pagination',
                    clickable: true,
                },
                navigation: {
                    nextEl: '.' + containerId + ' .swiper-button-next',
                    prevEl: '.' + containerId + ' .swiper-button-prev',
                },
                breakpoints: {
                    768: {
                        slidesPerView: tabletColumns, // Tablet
                    },
                    1024: {
                        slidesPerView: desktopColumns, // Desktop
                    }
                }
            });
        });
        
        // Add to cart AJAX functionality if needed
        $('.giodc-woo-swiper-product .button.ajax_add_to_cart').on('click', function(e) {
            // Additional AJAX functionality can be added here if needed
        });
    }

    // Initialize when DOM is fully loaded
    $(document).ready(function() {
        initSwipers();
    });

    // Re-initialize when new content is loaded via AJAX
    $(document).on('ajaxComplete', function() {
        initSwipers();
    });

})(jQuery);
