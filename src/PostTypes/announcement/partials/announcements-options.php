<?php

global $post;

// Nonce field to validate form request came from current site
wp_nonce_field( basename( __FILE__ ), 'metabox_announcement_options' );

?>


<div class="fluid-container">

    <div class="row">
        <?php $announcement_option_show_global = get_post_meta($post->ID, 'announcement_option_show_global', true); ?>

        <div class="col-12 d-flex">
            <div class="col-3">
                <input type="checkbox" class="" id="announcement_option_show_global" name="announcement_option_show_global" <?php if($announcement_option_show_global) { echo 'checked'; } ?>>
            </div>
            <div class="col">
                <label class="" for="workshop_option_highlight">Global anzeigen</label>
            </div>
        </div>

    </div>
</div>