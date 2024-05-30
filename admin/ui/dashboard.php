<div class="wrap">
<div class="blogify">
    <?php require_once 'components/header.php'; ?>    
    <main>
        <article class="blogify-status-bar">
        <?php 
            require_once 'components/status-card.php';
            echo blogify_status_card() .
             blogify_status_card() .
             blogify_status_card() .
             blogify_status_card();
        ?>  
        </article>
            <article class="blogify-blog-list">
                <section class="blogify-header">
                    <span class ="blogify-left">
                        <span class="blogify-title">My Blogs</span>
                    </span>
                    <span class="blogify-right">
                        <button type="button" class="blogify-primary">View All</button>
                        <button type="button" class="blogify-primary">Create</button>
                    </span>
                </section>
                <section class="blogify-items">      
                    <?php require_once 'components/blog-item.php';
                        echo blogify_blog_item() .
                         blogify_blog_item() .
                        blogify_blog_item() .
                        blogify_blog_item();
                    ?>
                    
                </section>
            </article>
            <?php require_once 'components/category-list.php'; ?>
        </main>
    </div>
</div>