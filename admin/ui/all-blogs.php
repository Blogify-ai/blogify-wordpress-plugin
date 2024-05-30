<div class="wrap">
    <div class="blogify">
    <?php require_once 'components/header.php'; ?>   
        <main>
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
                        $blog_item = blogify_blog_item();
                        echo array_reduce(
                            array_fill(0, 10, $blog_item),
                            fn($a, $b) => $a . $b,
                            ""
                        );
                    ?>
                </section>
            </article>
        </main>
        <footer>
            
                <?php require_once 'components/pagination.php'; ?>
                
        </footer>
    </div>
</div>