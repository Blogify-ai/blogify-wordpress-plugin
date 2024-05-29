<?php
    $asset_base = plugin_dir_url(__FILE__) . '../assets/';
?>

<div class="wrap">
    <main>
        <section class="logos">
            <img id="blogify-banner" src="<?php echo $asset_base; ?>/images/logos/blogify-banner.png" alt="Blogify Logo">
            <img id="two-way-arrow" src="<?php echo $asset_base; ?>/images/icons/arrow-goes-left-right-icon.svg"
                alt="Arrow Goes Left Right Icon">
            <img id="wordpress-banner" src="<?php echo $asset_base; ?>/images/logos/wordpress-black-banner.png" alt="WordPress Logo">
        </section>
        <article class="redirect">
            <span class="instructions">
                <h1>Seamless Integration with <?php echo ucfirst(wp_get_current_user()->display_name . "'s" ?? "Your"); ?> WordPress Site</h1>
                <span class="description">
                    <h3>To establish a connection, a prompt may appear requesting authorization.<br>
                        Should the automatic process not initiate, kindly click the <a
                            href="http://example.com/alternate_url.html">link</a> here to proceed.
                    </h3>
                </span>
        </article>
    </main>
</div>
<script>
    const redirect = () => window.location.assign("http://example.com/alternate_url.html")
    //setTimeout(redirect, 5000);
</script>