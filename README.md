# Giodc WooCommerce Swiper

A WordPress plugin that displays WooCommerce products in beautiful, responsive Swiper JS carousels.

## Description

Giodc WooCommerce Swiper provides a set of shortcodes to display your WooCommerce products in responsive, touch-enabled carousels powered by Swiper JS. Perfect for showcasing featured products, new arrivals, products by category, tag, or attribute on any page of your website.

## Features

- Multiple shortcodes for different product selection methods
- Fully responsive with customizable breakpoints
- Touch-enabled navigation for mobile devices
- Customizable number of columns for desktop, tablet, and mobile
- Option to hide pagination dots
- Compatible with WooCommerce's AJAX add-to-cart functionality
- Lightweight and fast

## Installation

1. Upload the `giodc-woo-swiper` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Make sure WooCommerce is installed and activated
4. Use the shortcodes in your pages or posts

## Shortcodes

### Featured Products

Display featured products in a swiper carousel.

```
[giodc_featured_products limit="12" columns="5" orderby="date" order="desc" hide_dots="no"]
```

### Recent Products

Display your most recent products.

```
[giodc_recent_products limit="12" columns="5" orderby="date" order="desc" hide_dots="no"]
```

### Products by Category

Display products from specific categories.

```
[giodc_products_by_category limit="12" columns="5" orderby="date" order="desc" category="clothing,accessories" operator="IN" hide_dots="no"]
```

### Products by Tag

Display products with specific tags.

```
[giodc_products_by_tag limit="12" columns="5" orderby="date" order="desc" tags="featured,sale" operator="IN" hide_dots="no"]
```

### Products by Attribute

Display products with specific attributes.

```
[giodc_products_by_attribute limit="12" columns="5" orderby="date" order="desc" attribute="color" terms="red,blue" operator="IN" hide_dots="no"]
```

### Products on Sale

Display products that are currently on sale.

```
[giodc_sale_products limit="12" columns="5" orderby="date" order="desc" hide_dots="no"]
```

### Best Selling Products

Display your best selling products.

```
[giodc_best_selling_products limit="12" columns="5" hide_dots="no"]
```

### Top Rated Products

Display your top rated products.

```
[giodc_top_rated_products limit="12" columns="5" orderby="date" order="desc" hide_dots="no"]
```

## Shortcode Parameters

All shortcodes accept the following parameters:

| Parameter | Description | Default | Options |
|-----------|-------------|---------|---------|
| `limit` | Number of products to display | `12` | Any positive number |
| `columns` | Number of columns on desktop | `5` | Any positive number |
| `orderby` | How to sort the products | `date` | `date`, `price`, `rand`, `title`, `popularity`, `rating` |
| `order` | Sort order | `desc` | `asc`, `desc` |
| `hide_dots` | Whether to hide pagination dots | `no` | `yes`, `no` |

### Additional parameters for specific shortcodes:

#### Products by Category
| Parameter | Description | Default | Options |
|-----------|-------------|---------|---------|
| `category` | Category slug(s) | empty | Comma-separated list of category slugs |
| `operator` | Operator to compare categories | `IN` | `IN`, `AND`, `NOT IN` |

#### Products by Tag
| Parameter | Description | Default | Options |
|-----------|-------------|---------|---------|
| `tags` | Tag slug(s) | empty | Comma-separated list of tag slugs |
| `operator` | Operator to compare tags | `IN` | `IN`, `AND`, `NOT IN` |

#### Products by Attribute
| Parameter | Description | Default | Options |
|-----------|-------------|---------|---------|
| `attribute` | Attribute name | empty | Any product attribute (e.g., `color`, `size`) |
| `terms` | Attribute term slug(s) | empty | Comma-separated list of term slugs |
| `operator` | Operator to compare terms | `IN` | `IN`, `AND`, `NOT IN` |

## Responsive Behavior

The plugin automatically adjusts the number of visible slides based on screen size:

- Desktop: Number of columns specified in the shortcode (default: 5)
- Tablet: 4 columns
- Mobile: 2 columns

## Examples

### Display 8 featured products with 4 columns and hidden pagination dots
```
[giodc_featured_products limit="8" columns="4" hide_dots="yes"]
```

### Display products from the "clothing" category sorted by price in ascending order
```
[giodc_products_by_category category="clothing" orderby="price" order="asc"]
```

### Display products with both "red" and "small" attribute terms
```
[giodc_products_by_attribute attribute="color,size" terms="red,small" operator="AND"]
```

### Display top rated products with 3 columns
```
[giodc_top_rated_products limit="10" columns="3"]
```

## Developers

### CSS Customization

The plugin includes minimal styling to ensure the carousel works correctly. You can add your own CSS to customize the appearance of the carousel and products.

Key CSS classes:

- `.giodc-woo-swiper-container`: The main container
- `.swiper`: The Swiper JS container
- `.swiper-wrapper`: The wrapper for slides
- `.swiper-slide`: Individual slides
- `.giodc-woo-swiper-product`: Product container
- `.giodc-woo-swiper-product-title`: Product title
- `.giodc-woo-swiper-product-price`: Product price

### Filters

The plugin includes filters that allow developers to modify the behavior:

- `giodc_woo_swiper_product_html`: Modify the HTML output for each product
- `giodc_woo_swiper_query_args`: Modify the query arguments for retrieving products

## Compatibility

- WordPress 5.0+
- WooCommerce 3.0.0+
- Works with most WordPress themes
- Tested with major browsers (Chrome, Firefox, Safari, Edge)

## Changelog

### 1.0.4
- Minor bug fixes and improvements

### 1.0.3
- Minor bug fixes and improvements

### 1.0.2
- Added option to hide pagination dots with `hide_dots="yes"` parameter
- Fixed compatibility with WooCommerce's AJAX add-to-cart functionality
- Minor bug fixes and improvements

### 1.0.1
- Added responsive breakpoints for better mobile experience
- Fixed CSS issues on some themes

### 1.0.0
- Initial release

## Credits

- Built with [Swiper](https://swiperjs.com/) v10.0.0
- Developed by [Giovanni De Carlo](https://giodc.com)