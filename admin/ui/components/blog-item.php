<?php

function blogify_blog_item() { 
    return <<<END
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
        END;
}