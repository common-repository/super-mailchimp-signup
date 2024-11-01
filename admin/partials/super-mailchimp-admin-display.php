<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://anderskristo.me
 * @since      1.0.0
 *
 * @package    Super_Mailchimp
 * @subpackage Super_Mailchimp/admin/partials
 */

$data = $this->mailchimp_get_meta_data();

$available_languages = array(
    'no',
    'sv',
);
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<?php if (isset($_GET['msg']) && $_GET['msg'] == 'succces' && isset($_GET['response']) && $_GET['response'] == '200') : ?>
    <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
<?php endif; ?>

<div class="wrap">
    <h2>Super MailChimp Signup</h2>

    <form name="" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="mailchimp_admin_submit">
        <input type="hidden" name="mailchimp_meta_nonce" value="<?php echo wp_create_nonce('mailchimp_meta_form_nonce'); ?>">

        <h3 class="title">Auth Settings</h3>
        
    	<table class="form-table">
        	<tbody>
        		<tr>
        			<th><?php _e('Mailchimp API Key:'); ?></th>
        			<td><input class="regular-text" type="text" name="mailchimp_api_key" value="<?php echo (isset($data['api_key']) ? $data['api_key'] : ''); ?>"></td>
        		</tr>
        	</tbody>
        </table>

        <h3 class="title">Lists</h3>
        
    	<table class="form-table">
        	<tbody>
                <tr>
                    <th><?php _e('Select list:'); ?></th>
                    <td>
                        <select name="selected_list">
                            <option value="-1" selected>Select a list...</option>

                            <?php if (isset($data['lists']) && isset($data['lists']['data'])) : ?>
                                <?php foreach ($data['lists']['data'] as $list) : ?>
                                    <option
                                        value="<?php echo $list['id']; ?>"
                                        <?php echo ($data['selected_list'] === $list['id'] ? 'selected' : ''); ?>
                                    >
                                        <?php echo $list['name']; ?>
                                    </option>
                                <?php endforeach; ?>                                        
                            <?php endif; ?>
                        </select>
                    </td>
                 </tr>
        	</tbody>
        </table>

        <h3 class="title">Other</h3>
        
    	<table class="form-table">
        	<tbody>
                 <tr>
        			<th><?php _e('Language:'); ?></th>
        			<td>
                        <select name="selected_lang">
                            <option value="-1" selected>Select a language...</option>

                            <?php if (isset($available_languages)) : ?>
                                <?php foreach ($available_languages as $lang) : ?>
                                    <option
                                        value="<?php echo $lang; ?>"
                                        <?php echo ($data['selected_lang'] === $lang ? 'selected' : ''); ?>
                                    >
                                        <?php echo $lang; ?>
                                    </option>
                                <?php endforeach; ?>                                        
                            <?php endif; ?>
                        </select>
                    </td>
        		</tr>
                <tr>
        			<th><?php _e('Terms Link:'); ?></th>
        			<td><input class="regular-text" type="text" name="terms_link" value="<?php echo (isset($data['terms_link']) ? $data['terms_link'] : ''); ?>"></td>
        		</tr>
        	</tbody>
        </table>
        <hr>
    	<br>
        <input class="button button-primary" type="submit" name="Submit" value="<?php _e('Update Options'); ?>" />
    </form>
</div>
