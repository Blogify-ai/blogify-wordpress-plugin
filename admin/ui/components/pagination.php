<?php

namespace PixelShadow\Blogify;


if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}


/**
 * Determines a navigation bar for pagination.
 *
 * @param int $number_of_pages The total number of pages.
 * @param int $current_page The current page number.
 * @param int $breadth The number of pages to display before and after the current page. Default is 2.
 * @return array The navigation bar as an array.
 */

function blogify_determine_page_navigation_bar(int $number_of_pages, int $current_page, int $breadth = 2): array {
    $numbers = array_filter(
        range(1, $number_of_pages),
        fn($num) => $num >= ($current_page - $breadth) && $num <= ($current_page + $breadth)
    );

    $prefix = array_filter($numbers, fn($num) => $num < $current_page);
    $postfix = array_filter($numbers, fn($num) => $num > $current_page);

    $preArrow = reset($numbers) !== 1;
    $postArrow = end($numbers) !== $number_of_pages;

    return [
        'preArrow' => $preArrow,
        'prefix' => array_values($prefix),
        'core' => $current_page,
        'postfix' => array_values($postfix),
        'postArrow' => $postArrow
    ];
}

function blogify_page_navigation_bar(int $number_of_pages, int $current_page ): void {
    $navigation_bar = blogify_determine_page_navigation_bar($number_of_pages, $current_page);
?>
    <span class='blogify-page-numbers'>
        <?php if ($navigation_bar['preArrow']): ?> 
            <button type='submit' name='page-number' value='1' class='blogify-secondary'>
                <img class='blogify-arrow-jump' src="<?php echo esc_url(BLOGIFY_IMAGES_URL . 'icons/arrow-end-left.svg') ?>" />
            </button>
        <?php endif ?>
        <?php foreach($navigation_bar['prefix'] as $num): ?>
            <button type='submit' name='page-number' value='<?php echo esc_attr($num) ?>' class='blogify-secondary'>
            <?php echo esc_html($num) ?>
        </button>
        <?php endforeach ?>
        <button type='button' class='blogify-primary'> <?php echo esc_html($navigation_bar['core']) ?> </button>
        <?php foreach($navigation_bar['postfix'] as $num): ?>
            <button type='submit' name='page-number' value='<?php echo esc_attr($num) ?>' class='blogify-secondary'>
            <?php echo esc_html($num) ?>
        </button>
        <?php endforeach ?>
        <?php if ($navigation_bar['postArrow']): ?>
            <button type='submit' name='page-number' value='<?php echo esc_attr($number_of_pages)?>' class='blogify-secondary'>
                <img class='blogify-arrow-jump' src="<?php echo esc_url(BLOGIFY_IMAGES_URL . 'icons/arrow-end-right.svg') ?>" />
            </button>
        <?php endif ?>
    </span>
<?php }

function blogify_page_info(int $total_blogs, int $current_page, int $page_size, int $total_pages): void {
    $starting_blog = ($current_page - 1) * $page_size + 1;
    $ending_blog = $current_page === $total_pages ? $total_blogs :  $starting_blog + $page_size - 1;
    ?>    
    <span class='blogify-page-info'>
        <span class='blogify-page-stats'>
            <?php echo esc_html("Showing Results {$starting_blog} - {$ending_blog} of $total_blogs") ?>
        </span>
    </span>
<?php
}

function blogify_pagination(int $current_page, int $total_blogs, int $page_size, int $total_pages, string $nonce): void {
    $page= 'blogify-all-blogs';
    ?>
    <form method='GET' action='<?php echo esc_url(admin_url( "admin.php" )) ?>'>
        <input type='hidden' name='page' value='<?php echo esc_attr($page)?>' />
        <input type='hidden' name='blogify-pagination-nonce' value='<?php echo esc_attr($nonce)?>' />
        <span class='blogify-pagination'>    
            <?php blogify_page_navigation_bar($total_pages, $current_page) ?>
            <?php blogify_page_info($total_blogs, $current_page, $page_size, $total_pages) ?>
        </span>
    </form>
    <?php
}
