<?php
// بسم الله الرحمن الرحيم

namespace PixelShadow\Blogify\UI;
function blogify_publish_dialog(string $id, string $blog_id, string $nonce, string $page, int $page_number)
{
    ?>
    <dialog id="<?php echo esc_attr($id) ?>">
        <h1>Do you wish to publish this blog?</h1>
        <form method="post" action='<?php echo esc_url(admin_url("admin.php") . "?" . http_build_query(
            [
                "page" => $page,
                "blogify-pagination-nonce" => $nonce,
                "page-number" => $page_number
            ]
        )) ?>'>
            <input type="hidden" name="blog_id" value='<?php echo esc_attr($blog_id) ?>' />
            <fieldset>
                <legend>Author</legend>
                <?php foreach (get_users(['fields' => ['ID', 'display_name'], 'orderby' => 'display_name']) as $author): ?>
                    <label>
                        <input required type="radio" name="author" value="<?php echo esc_attr($author->id) ?>" />
                        <span
                            style="display: inline-flex; flex-direction:column; justify-content: space-around; align-items: center;">
                            <img style="width: 20px; height: 20px;" src="<?php echo esc_url(get_avatar_url($author->id)) ?>"
                                alt="<?php echo esc_attr($author->display_name . ' avatar') ?>" />
                            <span><?php echo esc_html($author->display_name) ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </fieldset>
            <fieldset>
                <legend>Post Status</legend>
                <?php foreach (get_post_statuses() as $value => $name): ?>
                    <label>
                        <input required type="radio" name="post_status" value="<?php echo esc_attr($value) ?>" />
                        <span><?php echo esc_html($name) ?></span>
                    </label>
                <?php endforeach; ?>
            </fieldset>
            <fieldset>
                <legend>Categories</legend>
                <?php foreach (get_categories(['orderby' => 'name', 'order' => 'ASC', 'hide_empty' => false]) as $category): ?>
                    <label>
                        <input type="checkbox" name="categories[]" value="<?php echo esc_attr($category->cat_ID) ?>" />
                        <span><?php echo esc_html($category->cat_name) ?></span>
                    </label>
                <?php endforeach; ?>
            </fieldset>
            <menu>
                <button class="blogify-secondary" type="button"
                    onclick="<?php echo esc_js($id . '.close()') ?>">Cancel</button>
                <button style="background-color: var(--blogify-primary-color); color: white" class="blogify-secondary"
                    type="submit">Publish</button>
            </menu>
        </form>
    </dialog>
<?php }