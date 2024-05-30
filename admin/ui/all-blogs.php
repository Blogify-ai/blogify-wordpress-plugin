<div class="wrap">
    <div class="blogify">
    <?php require_once 'components/header.php'; ?>   
        <main>
            <article class="blogify-blog-list">
                <section class="blogify-header">
                    <span class="blogify-title">My Blogs</span>
                    <button type="button" class="blogify-primary">Create Blog</button>
                </section>
                <section class="blogify-items">
                    <span class="blogify-left">
                        <img class="blogify-blog-cover" alt="Blog Cover"
                            src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                        <span class="blogify-blog-info">
                            <span class="blogify-blog-title">Gardening in my free time</span>
                            <span class="blogify-blog-stats">Architecture · Scheduled · 2331 Words · 0 Links</span>
                        </span>
                    </span>
                    <span class="blogify-right">
                        <button type="button" class="blogify-secondary">View</button>
                        <button type="button" class="blogify-secondary">Edit</button>
                    </span>
                </section>
                <section class="blogify-items">
                    <span class="blogify-left">
                        <img class="blogify-blog-cover" alt="Blog Cover"
                            src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                        <span class="blogify-blog-info">
                            <span class="blogify-blog-title">Gardening in my free time</span>
                            <span class="blogify-blog-stats">Architecture · Scheduled · 2331 Words · 0 Links</span>
                        </span>
                    </span>
                    <span class="blogify-right">
                        <button type="button" class="blogify-secondary">View</button>
                        <button type="button" class="blogify-secondary">Edit</button>
                    </span>
                </section>
                <section class="blogify-items">
                    <span class="blogify-left">
                        <img class="blogify-blog-cover" alt="Blog Cover"
                            src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                        <span class="blogify-blog-info">
                            <span class="blogify-blog-title">Gardening in my free time</span>
                            <span class="blogify-blog-stats">Architecture · Scheduled · 2331 Words · 0 Links</span>
                        </span>
                    </span>
                    <span class="blogify-right">
                        <button type="button" class="blogify-secondary">View</button>
                        <button type="button" class="blogify-secondary">Edit</button>
                    </span>
                </section>
            </article>
        </main>
        <footer>
            <span class="blogify-pagination">
                <span class="blogify-page-numbers">
                    <button type="button" class="blogify-secondary">
                        <img class="blogify-arrow-jump" src="<?php echo $image_base; ?>/images/icons/arrow-end-left-icon.svg" />
                    </button>
                    <button type="button" class="blogify-secondary">
                        3
                    </button>
                    <button type="button" class="blogify-secondary">
                        4
                    </button>
                    <button type="button" class="blogify-primary">
                        5
                    </button>
                    <button type="button" class="blogify-secondary">
                        6
                    </button>
                    <button type="button" class="blogify-secondary">
                        7
                    </button>
                    <button type="button" class="blogify-secondary">
                        <img class="blogify-arrow-jump" src="<?php echo $image_base; ?>/images/icons/arrow-end-right-icon.svg" />
                    </button>
                </span>
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
            </span>
        </footer>
    </div>
</div>