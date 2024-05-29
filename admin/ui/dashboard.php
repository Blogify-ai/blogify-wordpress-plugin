<?php
$image_base = plugin_dir_url(__FILE__);
?>
<div class="wrap">
<div class="blogify">
    <header>
        <div class="left">
            <img id="blogify-banner" alt="Blogify" src="<?php echo $image_base; ?>/images/logos/blogify-banner.png" />
        </div>
        <div class="right">
            <span id="credits">
                <img id="credits-logo" alt="Credits" src="<?php echo $image_base; ?>/images/logos/blogify-logo-white.png" />
                <span>
                    Credits: 800
                </span>
            </span>
            <img class="icon" alt="Settings" src="<?php echo $image_base; ?>/images/icons/settings-gear-icon.svg" />
            <img class="icon" hidden alt="Notification" src="<?php echo $image_base; ?>/images/icons/notification-icon.svg" />
            <img id="user-avatar" alt="User Avatar" src="<?php echo $image_base; ?>/images/logos/blogify-banner-light.svg" />
        </div>
    </header>
    <main>
        <article class="status-bar">
            <article class="status-card">
                <span class="title">Draft</span>
                <span class="value">5</span>
                <span class="info">Total Blogs: 5</span>
            </article>
            <article class="status-card">
                <span class="title">Scheduled</span>
                <span class="value">1</span>
                <span class="info">Next Blog : <time datetime="2023-05-18T08:54:00">08:54 AM, 18 May 23</time></span>
            </article>
            <article class="status-card">
                <span class="title">Published</span>
                <span class="value">2</span>
                <span class="info">Active Links: 2</span>
            </article>
            <article class="status-card">
                <span class="title">Earnings</span>
                <span class="value"">$0.00</span>
            <span class=" info">Balance: $0.00</span>
            </article>
        </article>
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
                            <span class="blog-stats">Architecture  ·  Scheduled  ·  2331 Words  ·  0 Links</span>
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
                            <span class="blog-stats">Architecture  ·  Scheduled  ·  2331 Words  ·  0 Links</span>
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
                            <span class="blog-stats">Architecture  ·  Scheduled  ·  2331 Words  ·  0 Links</span>
                        </span>
                    </span>
                    <span class="right">
                        <button type="button" class="secondary">View</button>
                        <button type="button" class="secondary">Edit</button>
                    </span>
                </section>
                <section class="footer">
                    <a>View All Blogs</a>
                </section>
            </article>
            <article class="category-list">
                <section class="header">
                    <span class="title">My Categories</span>
                    <button type="button" class="secondary">Create</button>
                </section>
            <section class="items">
                <span class="category-info">
                    <span class="category-name">Herbs</span>
                    <span class="category-stats">Draft: 8  ·  Scheduled: 2  ·  Published : 16</span>
                </span>
                <span class="category-info">
                    <span class="category-name">Herbs</span>
                    <span class="category-stats">Draft: 8  ·  Scheduled: 2  ·  Published : 16</span>
                </span>
                <span class="category-info">
                    <span class="category-name">Herbs</span>
                    <span class="category-stats">Draft: 8  ·  Scheduled: 2  ·  Published : 16</span>
                </span>
                <span class="category-info">
                    <span class="category-name">Herbs</span>
                    <span class="category-stats">Draft: 8  ·  Scheduled: 2  ·  Published : 16</span>
                </span>
                <span class="category-info">
                    <span class="category-name">Herbs</span>
                    <span class="category-stats">Draft: 8  ·  Scheduled: 2  ·  Published : 16</span>
                </span>
            </article>
        </main>
    </div>
</div>