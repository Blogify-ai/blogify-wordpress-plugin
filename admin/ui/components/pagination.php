<?php
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

function blogify_page_navigation_bar(int $number_of_pages, int $current_page) {
    $navigation_bar = determinePageNavigationBar($number_of_pages, $current_page);
    $goto_first_button = $navigation_bar['preArrow'] ? 
                            <<<END
                            <button type="button" class="blogify-secondary">
                                <img class="blogify-arrow-jump" src="$image_base/icons/arrow-end-left-icon.svg" />
                            </button>
                            END
                            : '';
    $goto_last_button = $navigation_bar['postArrow'] ?
                        <<<END
                        <button type="button" class="blogify-secondary">
                            <img class="blogify-arrow-jump" src="$image_base/icons/arrow-end-right-icon.svg" />
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
        <button type="button" class="blogify-secondary">
            $num
        </button>
        END
        , $navigation_bar['prefix']
    ));

    $succeeding_page_buttons = implode("\n", array_map(
        fn($num) => <<<END
        <button type="button" class="blogify-secondary">
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

function blogify_page_info(int $number_of_pages, int $current_page) {
            return <<<END
                <span class="blogify-page-info"">
                    <span class="blogify-page-stats">
                        Showing Results 1 - 10 of 77
                    </span>
                    <label>Number of Items to Display:
                    <select name="page-limit" id="page-limit">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">50</option>
                    </select>
                    </label>
                </span>
                END;
}


?>
<span class="blogify-pagination">
            <?php  echo blogify_page_navigation_bar(77, 1) .
                    blogify_page_info(77, 1);
            ?>                   
            </span>
