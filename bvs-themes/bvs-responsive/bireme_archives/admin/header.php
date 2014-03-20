<?php $header = $settings['header']; ?>
<tr>
	<th>Logo</th>
	<th>URL da Imagem</th>
	<th>Link</th>
</tr>
<tr>
	<th><label>PT</label></th>
	<td><input id="header[logo-pt]" name="header[logo-pt]" placeholder="<?php echo __('Paste the URL','vhl');?>" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["logo-pt"] ) ); ?>"></td>
	<td><input id="header[linkLogo-pt]" name="header[linkLogo-pt]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["linkLogo-pt"] ) ); ?>"><br/></td>
</tr>
<tr>
	<th><label>ES</label></th>
	<td><input id="header[logo-es]" name="header[logo-es]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["logo-es"] ) ); ?>"></td>
	<td><input id="header[linkLogo-es]" name="header[linkLogo-es]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["linkLogo-es"] ) ); ?>"></td>
</tr>
<tr>
	<th><label>EN</label></th>
	<td><input id="header[logo-en]" name="header[logo-en]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["logo-en"] ) ); ?>"></td>
	<td><input id="header[linkLogo-en]" name="header[linkLogo-en]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["linkLogo-en"] ) ); ?>"></td>
</tr>
<tr>
	<td colspan="3"><hr/></td>
</tr>
<tr>
	<th>Banner</th>
	<th>URL da Imagem</th>
	<th>Link</th>
</tr>
<tr>
	<th><label>PT</label></th>
	<td><input id="header[banner-pt]" name="header[banner-pt]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["banner-pt"] ) ); ?>"></td>
	<td><input id="header[bannerLink-pt]" name="header[bannerLink-pt]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["bannerLink-pt"] ) ); ?>"></td>
</tr>
<tr>
	<th><label>ES</label></th>
	<td><input id="header[banner-es]" name="header[banner-es]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["banner-es"] ) ); ?>"></td>
	<td><input id="header[bannerLink-es]" name="header[bannerLink-es]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["bannerLink-es"] ) ); ?>"></td>
</tr>
<tr>
	<th><label>EN</label></th>
	<td><input id="header[banner-en]" name="header[banner-en]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["banner-en"] ) ); ?>"></td>
	<td><input id="header[bannerLink-en]" name="header[bannerLink-en]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["bannerLink-en"] ) ); ?>"></td>
</tr>
<tr>
	<th></th>
	<td><input id="header[title_view]" name="header[title_view]" type="checkbox" class="" value="true" <?php if($header['title_view'] == 'true') { echo "checked"; } ?> > <label for="header[language]"><?php echo __('Check to display title on banner','vhl');?></label></td>
	<td></td>
</tr>
<tr>
	<td colspan="3"><hr/></td>
</tr>
<tr>
	<th><?php echo __('Languages bar','vhl');?></th>
	<th><?php echo __('Available languages','vhl');?></th>
	<th><?php echo __('Language bar position','vhl');?></th>
</tr>
<tr>
	<th><label></label></th>
	<td><input id="header[language]" name="header[language]" type="checkbox" class="" value="true" <?php if($header['language'] == 'true') { echo "checked"; } ?> ><?php echo __('Check to display available languages','vhl');?></td>
	<td>
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
	<th><label for="header[bannerLink]">Contact page</label></th>
	<td colspan="2"><input id="header[contactPage]" name="header[contactPage]" type="text" class="regular-text code" value="<?php echo esc_html( stripslashes( $header["contactPage"] ) ); ?>"></td>
</tr>
<tr>
	<th><label for="header-extrahead"><?php echo __('Custom CSS and Javascript','vhl');?></label></th>
	<td colspan="2"><textarea id="header-extrahead" rows="7" cols="70" name="header[extrahead]"><?= stripslashes( $header['extrahead'] ) ?></textarea></td>
</tr>
