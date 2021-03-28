<div class="card">
    <div class="card-body">
        <?php
            if (Ark_has_post_thumbnail()){
                $thumbnail_url = Ark_get_post_thumbnail();
                echo "<img class='card-img-top' src='" . $thumbnail_url . "'></img>";
            }
        ?>
        <a href="<?php the_permalink(); ?>">
            <h5 class="card-title"><?php the_title(); ?></h5>
        </a>
        <hr />
        <?php
            // $preview = wp_trim_words(do_shortcode(get_the_content('...')), 175);
            $preview = wp_trim_words(get_the_content('...'), 175);
            if ($preview != "") {
                echo '<p class="card-text">' . $preview . '</p>';
            }
        ?>
    </div>
</div>
</br>