<div class="wrap">
    <div class="blogify">
    <?php require_once 'components/header.php'; ?>   
        <main>
            <article class="blog-list">
                <section class="header">
                    <span class="title">My Blogs</span>
                    <button type="button" class="primary">Create Blog</button>
                </section>
                <section class="items">
                    <span class="left">
                        <img class="blog-cover" alt="Blog Cover"
                            src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                        <span class="blog-info">
                            <span class="blog-title">Gardening in my free time</span>
                            <span class="blog-stats">Architecture · Scheduled · 2331 Words · 0 Links</span>
                        </span>
                    </span>
                    <span class="right">
                        <button type="button" class="secondary">View</button>
                        <button type="button" class="secondary">Edit</button>
                    </span>
                </section>
                <section class="items">
                    <span class="left">
                        <img class="blog-cover" alt="Blog Cover"
                            src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                        <span class="blog-info">
                            <span class="blog-title">Gardening in my free time</span>
                            <span class="blog-stats">Architecture · Scheduled · 2331 Words · 0 Links</span>
                        </span>
                    </span>
                    <span class="right">
                        <button type="button" class="secondary">View</button>
                        <button type="button" class="secondary">Edit</button>
                    </span>
                </section>
                <section class="items">
                    <span class="left">
                        <img class="blog-cover" alt="Blog Cover"
                            src="https://i.ytimg.com/vi/e4Tk-kcOmUA/maxresdefault.jpg" />
                        <span class="blog-info">
                            <span class="blog-title">Gardening in my free time</span>
                            <span class="blog-stats">Architecture · Scheduled · 2331 Words · 0 Links</span>
                        </span>
                    </span>
                    <span class="right">
                        <button type="button" class="secondary">View</button>
                        <button type="button" class="secondary">Edit</button>
                    </span>
                </section>
            </article>
        </main>
        <footer>
            <span class="pagination">
                <span class="page-numbers">
                    <button type="button" class="secondary">
                        <img class="arrow" src="<?php echo $image_base; ?>icons/arrow-end-left-icon.svg" />
                    </button>
                    <button type="button" class="secondary">
                        3
                    </button>
                    <button type="button" class="secondary">
                        4
                    </button>
                    <button type="button" class="primary">
                        5
                    </button>
                    <button type="button" class="secondary">
                        6
                    </button>
                    <button type="button" class="secondary">
                        7
                    </button>
                    <button type="button" class="secondary">
                        <img class="arrow" src="<?php echo $image_base; ?>/icons/arrow-end-right-icon.svg" />
                    </button>
                </span>
                <span class="page-info"">
                    <span class="page-stats">
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