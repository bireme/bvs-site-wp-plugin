<?php

    $header = $settings['header'];
    $current_language = strtolower(get_bloginfo('language'));
    $site_lang = substr($current_language, 0,2);

    if ( defined( 'POLYLANG_VERSION' ) )
        $langs = pll_languages_list();
    else
        $langs = array( $site_lang );

?>
<tr>
	<th></th>
	<th><?php echo __('Image URL','vhl');?></th>
	<th><?php echo __('Link','vhl');?></th>
</tr>

<?php foreach ($langs as $lang) { ?>
    <tr>
    	<th>
            <label><?php echo strtoupper(__('Logo','vhl'));?></label>
            <?php echo ( defined( 'POLYLANG_VERSION' ) ) ? '(' . strtoupper( $lang ) . ')' : ''; ?>
        </th>
    	<td><input id="header[logo-<?php echo $lang; ?>]" name="header[logo-<?php echo $lang; ?>]" placeholder="<?php echo __('Paste the URL','vhl');?>" type="text" class="regular-text code header-logo" value="<?php echo esc_html( stripslashes( $header["logo-" . $lang] ) ); ?>"></td>
    	<td><input id="header[linkLogo-<?php echo $lang; ?>]" name="header[linkLogo-<?php echo $lang; ?>]" placeholder="<?php echo __('Paste the link','vhl');?>" type="text" class="regular-text code header-logo-link" value="<?php echo esc_html( stripslashes( $header["linkLogo-" . $lang] ) ); ?>"><br/></td>
    </tr>
<?php } ?>

<tr>
	<td colspan="3"><hr/></td>
</tr>
<tr>
	<th></th>
	<th><?php echo __('Image URL','vhl');?></th>
	<th><?php echo __('Link','vhl');?></th>
</tr>

<?php foreach ($langs as $lang) { ?>
    <tr>
    	<th>
            <label><?php echo strtoupper(__('Banner','vhl'));?></label>
            <?php echo ( defined( 'POLYLANG_VERSION' ) ) ? '(' . strtoupper( $lang ) . ')' : ''; ?>
        </th>
    	<td><input id="header[banner-<?php echo $lang; ?>]" name="header[banner-<?php echo $lang; ?>]" placeholder="<?php echo __('Paste the URL','vhl');?>" type="text" class="regular-text code header-banner" value="<?php echo esc_html( stripslashes( $header["banner-" . $lang] ) ); ?>"></td>
    	<td><input id="header[bannerLink-<?php echo $lang; ?>]" name="header[bannerLink-<?php echo $lang; ?>]" placeholder="<?php echo __('Paste the link','vhl');?>" type="text" class="regular-text code header-banner-link" value="<?php echo esc_html( stripslashes( $header["bannerLink-" . $lang] ) ); ?>"></td>
    </tr>
<?php } ?>
<tr>
	<th></th>
	<td><input id="header[title_view]" name="header[title_view]" type="checkbox" class="" value="true" <?php if($header['title_view'] == 'true') { echo "checked"; } ?> > <?php echo __('Check to display title on banner','vhl');?></td>
	<td></td>
</tr>
<tr>
	<td colspan="3"><hr/></td>
</tr>
<tr>
	<th><?php echo __('Languages bar','vhl');?></th>
	<!--th><?php echo __('Available languages','vhl');?></th-->
	<th colspan="2"><?php echo __('Language bar position','vhl');?></th>
</tr>
<tr>
	<th></th>
	<!--td><input id="header[language]" name="header[language]" type="checkbox" class="" value="true" <?php if($header['language'] == 'true') { echo "checked"; } ?> ><?php echo __('Check to display available languages','vhl');?></td-->
	<td colspan="2">
		<label for="header-language-position"><?php echo __('Choose language bar position','vhl');?></label>
		<select class="header-language-position" id="language-position" name="header[language-position]">
			<option <?php if($header['language-position'] == "1") echo "selected='selected'"; ?> value="1"><?php echo __('Top','vhl');?></option>
			<option <?php if($header['language-position'] == "2") echo "selected='selected'"; ?> value="2"><?php echo __('Bottom','vhl');?></option>
		</select>
	</td>
</tr>
<tr>
	<td colspan="3"><hr/></td>
</tr>
<tr>
	<th><label for="header[headerMenu]"><?php echo __('Header Menu','vhl');?></label></th>
	<td colspan="2">
		<input id="header[headerMenu]" name="header[headerMenu]" type="checkbox" class="" value="true" <?php if($header['headerMenu'] == 'true') { echo "checked"; } ?> > <?php echo __('Check to hide header menu','vhl');?><br/>
	</td>
</tr>
<tr>
    <td colspan="3"><hr/></td>
</tr>
<?php
// Show contact text field only WP Contact Form 7 Plugin is active.
if(is_plugin_active('contact-form-7/wp-contact-form-7.php')) { ?>
<tr>
    <th><label for="header[contactPage]"><?php echo __('Contact page','vhl');?></label></th>
    <td colspan="2">
        <select id="header[contactPage]" name="header[contactPage]">
            <option></option>
        <?php
            $sufix = '';

            if(is_plugin_active('multi-language-framework/multi-language-framework.php')) {
                $default_language = mlf_get_option('default_language');

                if ($default_language != $site_lang)
                    $sufix = '_t_' . $site_lang;
            }

            // if ( defined( 'POLYLANG_VERSION' ) ) {
            //     $site_lang = pll_current_language();
            //     $default_language = pll_default_language();

            //     if ($default_language != $site_lang)
            //         $sufix = '_t_' . $site_lang;
            // }

            $args = array(
                'post_type'   => 'page' . $sufix,
                'post_status' => 'publish',
            );
            $loop = new WP_Query($args);

            while($loop->have_posts()): $loop->the_post();
            ?>
                <option value="<?php the_ID(); ?>" <?php $id = get_the_ID(); if ($header["contactPage"] == $id) echo "selected"; ?>><?php the_title(); ?></option>
            <?php
            endwhile;
            wp_reset_query();
        ?>
        </select><br />
    </td>
</tr>
<tr>
    <td colspan="3"><hr/></td>
</tr>
<?php } ?>

<!-- Option form activate login menu  -->

<tr>
    <th><label for="header[loginMenu]"><?php echo __('Login in the Top Menu','vhl');?></label></th>
    <td colspan="2">
        <input id="header[loginMenu]" name="header[loginMenu]" type="checkbox" class="" value="true" <?php if($header['loginMenu'] == 'true') { echo "checked"; } ?> > <label for="header[loginMenu]"> <?php echo __('Check to show login in the top menu','vhl');?></label><br/>
    </td>
</tr>
<tr>
    <td colspan="3"><hr/></td>
</tr>
<!-- End Login Menu -->


<tr>
	<th><label for="header-extrahead"><?php echo __('Custom CSS and Javascript','vhl');?></label></th>
	<td colspan="2">
		<textarea id="header-extrahead" rows="7" cols="70" name="header[extrahead]"><?= stripslashes( $header['extrahead'] ) ?></textarea>
	</td>
</tr>
