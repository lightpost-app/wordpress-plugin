<div class="lightpost">
    <div class="wrap">
        <h2 class="">
            <a href="https://lightpost.app">
                <img src="<?php echo plugins_url('img/lightpost-logo.svg', dirname(__FILE__)) ?>" height="100px;">
            </a>
        </h2>
        <p class="description">
            This plugin allows churches to display content from their <a href="https://lightpost.app">Lightpost</a> account on their Wordpress website.
        </p>
        <form method="post" action="options.php">
            <?php settings_fields('lightpost') ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">API key:</th>
                    <td>
                        <input type="password" name="lightpost_api_key" value="<?php echo esc_attr(get_option('lightpost_api_key')) ?>" style="min-width: 300px;" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Sermon Page:</th>
                    <td>
                        <select name="lightpost_sermon_archive_page_id" style="min-width: 300px;">
                            <option> -- none -- </option>
                            <?php foreach (get_pages(['post_status' => 'publish,inherit,pending,private,future,draft,trash']) as $page): ?>
                                <?php if (get_option('lightpost_sermon_archive_page_id') === (string) $page->ID): ?>
                                    <option selected="selected" value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Bible Class Registration Page:</th>
                    <td>
                        <select name="lightpost_bible_class_registration_page_id" style="min-width: 300px;">
                            <option> -- none -- </option>
                            <?php foreach (get_pages(['post_status' => 'publish,inherit,pending,private,future,draft,trash']) as $page): ?>
                                <?php if (get_option('lightpost_bible_class_registration_page_id') === (string) $page->ID): ?>
                                    <option selected="selected" value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Directory Page:</th>
                    <td>
                        <select name="lightpost_directory_page_id" style="min-width: 300px;">
                            <option> -- none -- </option>
                            <?php foreach (get_pages(['post_status' => 'publish,inherit,pending,private,future,draft,trash']) as $page): ?>
                                <?php if (get_option('lightpost_directory_page_id') === (string) $page->ID): ?>
                                    <option selected="selected" value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $page->ID ?>"><?php echo $page->post_title ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                        <em>This page MUST be password protected.</em>
                        <p class="description">
                            <label>
                                <input type="checkbox" name="lightpost_directory_disclaimer" value="true" <?php echo get_option('lightpost_directory_disclaimer') == 'true' ? 'checked="checked"' : null; ?> /> I understand that this page can expose personal information and must be properly protected.
                            </label>
                        </p>
                    </td>
                </tr>
                <!-- <tr valign="top">
                    <th scope="row">Theme:</th>
                    <td>
                        <select name="lightpost_theme" style="min-width: 300px;">
                            <option></option>
                            <?php foreach (['light', 'dark'] as $theme): ?>
                                <?php if (get_option('lightpost_theme') === $theme): ?>
                                    <option value="<?php echo $theme ?>" selected="selected"><?php echo ucwords($theme) ?></option>
                                <?php else: ?>
                                    <option value="<?php echo $theme ?>"><?php echo ucwords($theme) ?></option>
                                <?php endif ?>
                            <?php endforeach ?>
                        </select>
                        <p class="description">Helpful on non-Lightpost themes.</p>
                    </td>
                </tr> -->
            </table>
            <p class="submit">
                <input type="submit" class="button-primary" value="Save Changes" />
            </p>
        </form>
    </div>
</div>
