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

function determinePageNavigationBar(int $number_of_pages, int $current_page, int $breadth = 2): array {
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

function blogify_page_navigation_bar(int $number_of_pages, int $current_page, string $image_base) {
    $navigation_bar = determinePageNavigationBar($number_of_pages, $current_page);
    $goto_first_button = $navigation_bar['preArrow'] ? 
                            <<<END
                            <button type="submit" name="page-number" value="1" class="blogify-secondary">
                                <img class="blogify-arrow-jump" src="{$image_base}icons/arrow-end-left.svg" />
                            </button>
                            END
                            : '';
    $goto_last_button = $navigation_bar['postArrow'] ?
                        <<<END
                        <button type="submit" name="page-number" value="{$number_of_pages}" class="blogify-secondary">
                            <img class="blogify-arrow-jump" src="{$image_base}icons/arrow-end-right.svg" />
                        </button>
                        END
                        : '';
    $current_page_button = <<<END
                            <button type="button" class="blogify-primary">
                                {$navigation_bar['core']}
                            </button>
                            END;

    $preceeding_page_buttons = implode("\n", array_map(
        fn($num) => <<<END
        <button type="submit" name="page-number" value="{$num}" class="blogify-secondary">
            $num
        </button>
        END
        , $navigation_bar['prefix']
    ));

    $succeeding_page_buttons = implode("\n", array_map(
        fn($num) => <<<END
        <button type="submit" name="page-number" value="{$num}" class="blogify-secondary">
            $num
        </button>
        END
        , $navigation_bar['postfix']
    ));


    return <<<END
    <span class="blogify-page-numbers">
        $goto_first_button
        $preceeding_page_buttons
        $current_page_button
        $succeeding_page_buttons
        $goto_last_button
    </span>
    END;
}

function blogify_page_info(int $total_blogs, int $current_page, int $page_size, int $total_pages) {
    $starting_blog = ($current_page - 1) * $page_size + 1;
    $ending_blog = $current_page === $total_pages ? $total_blogs :  $starting_blog + $page_size - 1;
        
    return <<<END
                <span class="blogify-page-info">
                    <span class="blogify-page-stats">
                        Showing Results {$starting_blog} - {$ending_blog} of $total_blogs
                    </span>
                END;
}

function construct_pagination(int $current_page, int $total_blogs, int $page_size, int $total_pages, string $image_base, string $nonce) {
    $page_navigation_bar = blogify_page_navigation_bar($total_pages, $current_page, $image_base);
    $page_info = blogify_page_info($total_blogs, $current_page, $page_size, $total_pages);
    $form_action = admin_url( "admin.php" );
    return <<<END
        <form method="GET" action="{$form_action}">
            <input type="hidden" name="page" value="{$_GET['page']}" />
            <input type="hidden" name="blogify-pagination-nonce" value="{$nonce}" />

                <span class="blogify-pagination">    
                $page_navigation_bar
                $page_info
        </span>
        </form>
    END;
}

?>
