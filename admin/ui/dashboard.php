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
                    <section class="blogify-item">
                        <span class="blogify-left">
                            <img class="blogify-blog-cover" alt="Blog Cover"
                                src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                            <span class="blogify-blog-info">
                                <span class="blogify-blog-title">Gardening in my free time</span>
                                <span class="blogify-blog-stats">Architecture  ·  Scheduled  ·  2331 Words  ·  0 Links</span>
                            </span>
                        </span>
                        <span class="blogify-right">
                            <button type="button" class="blogify-secondary">View</button>
                            <button type="button" class="blogify-secondary">Edit</button>
                        </span>
                    </section>
                    <section class="blogify-item">
                        <span class="blogify-left">
                            <img class="blogify-blog-cover" alt="Blog Cover"
                                src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                            <span class="blogify-blog-info">
                                <span class="blogify-blog-title">Gardening in my free time</span>
                                <span class="blogify-blog-stats">Architecture  ·  Scheduled  ·  2331 Words  ·  0 Links</span>
                            </span>
                        </span>
                        <span class="blogify-right">
                            <button type="button" class="blogify-secondary">View</button>
                            <button type="button" class="blogify-secondary">Edit</button>
                        </span>
                    </section>
                    <section class="blogify-item">
                        <span class="blogify-left">
                            <img class="blogify-blog-cover" alt="Blog Cover"
                                src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                            <span class="blogify-blog-info">
                                <span class="blogify-blog-title">Gardening in my free time</span>
                                <span class="blogify-blog-stats">Architecture  ·  Scheduled  ·  2331 Words  ·  0 Links</span>
                            </span>
                        </span>
                        <span class="blogify-right">
                            <button type="button" class="blogify-secondary">View</button>
                            <button type="button" class="blogify-secondary">Edit</button>
                        </span>
                    </section>
                </section>
            </article>
            <?php require_once 'components/category-list.php'; ?>
        </main>
    </div>
</div>