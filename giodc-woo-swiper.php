<?php
/**
 * Plugin Name: Giodc Woo Swiper
 * Plugin URI: https://github.com/giodc/giodc-woo-swiper
 * Description: WooCommerce product shortcodes displayed in Swiper JS carousel.
 * Version: 1.0.2
 * Author: Giovanni De Carlo
 * Author URI: https://giodc.com
 * Text Domain: giodc-woo-swiper
 * Domain Path: /languages
 * WC requires at least: 3.0.0
 * WC tested up to: 8.0.0
 * 
 * @package Giodc_Woo_Swiper
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('GIODC_WOO_SWIPER_VERSION', '1.0.0');
define('GIODC_WOO_SWIPER_PATH', plugin_dir_path(__FILE__));
define('GIODC_WOO_SWIPER_URL', plugin_dir_url(__FILE__));

/**
 * Check if WooCommerce is active
 */
if (!function_exists('giodc_woo_swiper_is_woocommerce_active')) {
    function giodc_woo_swiper_is_woocommerce_active() {
        return in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')));
    }
}

/**
 * Main plugin class
 */
class Giodc_Woo_Swiper {
    /**
     * Singleton instance
     *
     * @var Giodc_Woo_Swiper
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return Giodc_Woo_Swiper
     */
    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Only load if WooCommerce is active
        if (!giodc_woo_swiper_is_woocommerce_active()) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }

        // Load plugin text domain
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
        
        // Initialize the plugin
        $this->init();
    }

    /**
     * Initialize the plugin
     */
    public function init() {
        // Register shortcodes
        $this->register_shortcodes();
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Declare HPOS compatibility
        add_action('before_woocommerce_init', array($this, 'declare_hpos_compatibility'));
    }
    
    /**
     * Declare compatibility with WooCommerce HPOS (High-Performance Order Storage)
     */
    public function declare_hpos_compatibility() {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
        }
    }

    /**
     * Load plugin text domain
     */
    public function load_plugin_textdomain() {
        load_plugin_textdomain('giodc-woo-swiper', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue Swiper JS
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css', array(), '10.0.0');
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', array(), '10.0.0', true);
        
        // Enqueue plugin styles and scripts
        wp_enqueue_style('giodc-woo-swiper', GIODC_WOO_SWIPER_URL . 'assets/css/giodc-woo-swiper.css', array('swiper-css'), GIODC_WOO_SWIPER_VERSION);
        wp_enqueue_script('giodc-woo-swiper', GIODC_WOO_SWIPER_URL . 'assets/js/giodc-woo-swiper.js', array('jquery', 'swiper-js'), GIODC_WOO_SWIPER_VERSION, true);
        
        // Pass variables to script
        wp_localize_script('giodc-woo-swiper', 'giodcWooSwiper', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        ));
    }

    /**
     * Register shortcodes
     */
    public function register_shortcodes() {
        add_shortcode('giodc_new_products', array($this, 'new_products_shortcode'));
        add_shortcode('giodc_back_in_stock', array($this, 'back_in_stock_shortcode'));
        add_shortcode('giodc_discounted_products', array($this, 'discounted_products_shortcode'));
        add_shortcode('giodc_products_by_category', array($this, 'products_by_category_shortcode'));
        add_shortcode('giodc_products_by_tag', array($this, 'products_by_tag_shortcode'));
        add_shortcode('giodc_products_by_tags', array($this, 'products_by_tags_shortcode'));
        add_shortcode('giodc_popular_products', array($this, 'popular_products_shortcode'));
        add_shortcode('giodc_featured_products', array($this, 'featured_products_shortcode'));
    }

    /**
     * New products shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function new_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'hide_dots' => 'no',
            'orderby' => 'date',
            'order' => 'desc',
        ), $atts, 'giodc_new_products');
        
        return $this->render_products_swiper($atts, 'new');
    }

    /**
     * Back in stock shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function back_in_stock_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'hide_dots' => 'no',
            'orderby' => 'date',
            'order' => 'desc',
        ), $atts, 'giodc_back_in_stock');
        
        return $this->render_products_swiper($atts, 'back_in_stock');
    }

    /**
     * Discounted products shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function discounted_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'hide_dots' => 'no',
            'orderby' => 'date',
            'order' => 'desc',
        ), $atts, 'giodc_discounted_products');
        
        return $this->render_products_swiper($atts, 'discounted');
    }

    /**
     * Products by category shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function products_by_category_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'hide_dots' => 'no',
            'orderby' => 'date',
            'order' => 'desc',
            'category' => '',
        ), $atts, 'giodc_products_by_category');
        
        return $this->render_products_swiper($atts, 'category');
    }

    /**
     * Products by tag shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function products_by_tag_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'hide_dots' => 'no',
            'orderby' => 'date',
            'order' => 'desc',
            'tag' => '',
        ), $atts, 'giodc_products_by_tag');
        
        return $this->render_products_swiper($atts, 'tag');
    }

    /**
     * Products by multiple tags shortcode
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function products_by_tags_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'hide_dots' => 'no',
            'orderby' => 'date',
            'order' => 'desc',
            'tags' => '',
            'operator' => 'IN',
        ), $atts, 'giodc_products_by_tags');
        
        // Debug information
        error_log('Giodc Woo Swiper - Tags shortcode called with: ' . print_r($atts, true));
        
        return $this->render_products_swiper($atts, 'tags');
    }
    
    /**
     * Popular products shortcode - shows products with highest sales count
     *
     * @param array $atts Shortcode attributes
     * @return string Shortcode output
     */
    public function popular_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'days' => '0', // 0 means all time, otherwise limit to X days
        ), $atts, 'giodc_popular_products');
        
        // Debug information
        error_log('Giodc Woo Swiper - Popular products shortcode called with: ' . print_r($atts, true));
        
        return $this->render_products_swiper($atts, 'popular');
    }

    /**
     * Featured products shortcode
     */
    public function featured_products_shortcode($atts) {
        $atts = shortcode_atts(array(
            'limit' => '12',
            'columns' => '5',
            'desktop_columns' => '5',
            'tablet_columns' => '4',
            'mobile_columns' => '2',
            'hide_dots' => 'no',
            'orderby' => 'date',
            'order' => 'desc',
        ), $atts, 'giodc_featured_products');
        
        return $this->render_products_swiper($atts, 'featured');
    }

    /**
     * Render products swiper
     *
     * @param array $atts Shortcode attributes
     * @param string $type Shortcode type
     * @return string Shortcode output
     */
    private function render_products_swiper($atts, $type) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => intval($atts['limit']),
            'orderby' => $atts['orderby'],
            'order' => $atts['order'],
            'post_status' => 'publish',
        );

        // Add specific query args based on shortcode type
        switch ($type) {
            case 'new':
                // New products (last 30 days)
                $args['date_query'] = array(
                    array(
                        'after' => '30 days ago',
                    ),
                );
                break;

            case 'back_in_stock':
                // Products that are in stock
                $args['meta_query'] = array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock',
                        'compare' => '=',
                    ),
                );
                break;

            case 'discounted':
                // Products on sale
                $args['meta_query'] = array(
                    'relation' => 'OR',
                    array( // Simple products
                        'key' => '_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'NUMERIC',
                    ),
                    array( // Variable products
                        'key' => '_min_variation_sale_price',
                        'value' => 0,
                        'compare' => '>',
                        'type' => 'NUMERIC',
                    ),
                );
                break;

            case 'category':
                // Products by category
                if (!empty($atts['category'])) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_cat',
                            'field' => 'slug',
                            'terms' => explode(',', $atts['category']),
                        ),
                    );
                }
                break;

            case 'tag':
                // Products by tag
                if (!empty($atts['tag'])) {
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'product_tag',
                            'field' => 'slug',
                            'terms' => explode(',', $atts['tag']),
                        ),
                    );
                }
                break;
                
            case 'tags':
                // Products by multiple tags with operator
                if (!empty($atts['tags'])) {
                    $operator = in_array(strtoupper($atts['operator']), array('IN', 'AND', 'NOT IN')) ? strtoupper($atts['operator']) : 'IN';
                    
                    // Clean and trim tag slugs
                    $tag_slugs = array_map('trim', explode(',', $atts['tags']));
                    $tag_slugs = array_filter($tag_slugs); // Remove empty values
                    
                    // Debug information
                    error_log('Giodc Woo Swiper - Tags query with: ' . print_r($tag_slugs, true) . ' and operator: ' . $operator);
                    
                    if (!empty($tag_slugs)) {
                        $args['tax_query'] = array(
                            array(
                                'taxonomy' => 'product_tag',
                                'field' => 'slug',
                                'terms' => $tag_slugs,
                                'operator' => $operator,
                            ),
                        );
                    }
                }
                break;
                
            case 'popular':
                // Popular products based on sales count
                $args['meta_key'] = 'total_sales';
                $args['orderby'] = 'meta_value_num';
                $args['order'] = 'desc';
                
                // If days parameter is set, limit to products sold in that time period
                if (!empty($atts['days']) && intval($atts['days']) > 0) {
                    $args['date_query'] = array(
                        array(
                            'after' => intval($atts['days']) . ' days ago',
                        ),
                    );
                    
                    error_log('Giodc Woo Swiper - Limiting popular products to last ' . intval($atts['days']) . ' days');
                }
                break;
                
            case 'featured':
                // Featured products
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                    ),
                );
                break;
        }

        // Get products
        $products = new WP_Query($args);
        
        // Debug query
        error_log('Giodc Woo Swiper - Query SQL: ' . $products->request);

        // Start output buffer
        ob_start();

        // Debug post count
        error_log('Giodc Woo Swiper - Found posts: ' . $products->post_count);
        
        if ($products->have_posts()) {
            // Generate unique ID for this swiper instance
            $swiper_id = 'giodc-swiper-' . uniqid();
            
            // Swiper container
            // Add data attributes for responsive columns
            $mobile_columns = intval($atts['mobile_columns']) ?: 2;
            $tablet_columns = intval($atts['tablet_columns']) ?: 4;
            $desktop_columns = intval($atts['desktop_columns']) ?: 5;
            $hide_dots = ($atts['hide_dots'] === 'yes') ? 'true' : 'false';
            ?>
            <div class="giodc-woo-swiper-container">
                <div class="swiper <?php echo esc_attr($swiper_id); ?>" 
                     data-mobile-columns="<?php echo esc_attr($mobile_columns); ?>"
                     data-tablet-columns="<?php echo esc_attr($tablet_columns); ?>"
                     data-desktop-columns="<?php echo esc_attr($desktop_columns); ?>"
                     data-hide-dots="<?php echo esc_attr($hide_dots); ?>">
                    <div class="swiper-wrapper">
                        <?php
                        while ($products->have_posts()) : $products->the_post();
                            global $product;
                            ?>
                            <div class="swiper-slide">
                                <div class="giodc-woo-swiper-product">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php
                                        if (has_post_thumbnail()) {
                                            echo woocommerce_get_product_thumbnail();
                                        } else {
                                            echo wc_placeholder_img();
                                        }
                                        ?>
                                        <h3><?php the_title(); ?></h3>
                                        <div class="price"><?php echo $product->get_price_html(); ?></div>
                                    </a>
                                    <?php
                                    echo apply_filters(
                                        'woocommerce_loop_add_to_cart_link',
                                        sprintf(
                                            '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                            esc_url($product->add_to_cart_url()),
                                            esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
                                            esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                                            isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                                            esc_html($product->add_to_cart_text())
                                        ),
                                        $product
                                    );
                                    ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="swiper-pagination"></div>
                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            </div>
            <?php
        } else {
            echo '<p>' . esc_html__('No products found.', 'giodc-woo-swiper') . '</p>';
        }

        wp_reset_postdata();

        return ob_get_clean();
    }

    /**
     * WooCommerce missing notice
     */
    public function woocommerce_missing_notice() {
        ?>
        <div class="error">
            <p><?php esc_html_e('Giodc Woo Swiper requires WooCommerce to be installed and active.', 'giodc-woo-swiper'); ?></p>
        </div>
        <?php
    }
}

// Initialize the plugin
function giodc_woo_swiper_init() {
    return Giodc_Woo_Swiper::get_instance();
}

// Start the plugin
giodc_woo_swiper_init();
