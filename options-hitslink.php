<?php
    $options = HitsLink::instance()->getOptions();
?>
<div class="wrap">
    <h2>HitsLink Configuration</h2>

    <p><img src="<?php echo esc_attr(plugins_url('hitslink-logo.png', __FILE__)); ?>" alt="HitsLinks"/></p>

    <form method="post" action="<?php echo esc_attr(admin_url('admin-post.php')); ?>">
        <p>HitsLink is a leading online service to track hundreds of real-time website statistics.</p>
        <p>
            Please <a href="http://www.extrememember.com/hitslink">sign up</a> for a free 30 day trial HitsLink account if you don't have one.
        </p>

        <table class="widefat">
            <tbody valign="middle">
                <tr>
                    <th scope="row" align="right">HitsLink Account&nbsp;ID:</th>
                    <td><input type="text" name="options[account_id]" value="<?php echo esc_attr($options['account_id']); ?>"/></td>
                    <td>
                        To find out your Account ID, please log into your HitsLink account and then visit
                        <a href="https://www.hitslink.com/get-code.aspx">this page</a>. Please find a line like
                        <code>wa_account="8C959691948C"</code> (example). In this case, <code>8C959691948C</code>
                        is your Account ID.<br/><br/>
                        Please see the image to the right for further details.
                    </td>
                    <td rowspan="2"><img src="<?php echo esc_attr(plugins_url('code.png', __FILE__)); ?>" alt="HistLink Code Sample"/></td>
                </tr>
                <tr>
                    <th scope="row" align="right">Location:</th>
                    <td><input type="text" name="options[location]" value="<?php echo esc_attr($options['location']); ?>"/></td>
                    <td>
                        To find out the Location, please log into your HitsLink account and then visit
                        <a href="https://www.hitslink.com/get-code.aspx" target="_blank">this page</a>. Please find a line like
                        <code>location=26</code> (example). In this case, <code>26</code> is your Location.
                    </td>
                </tr>
                <tr>
                    <th scope="row" align="right">Ignore Admins:</th>
                    <td><input type="checkbox" name="options[ignore_admins]" value="1"<?php checked($options['ignore_admins'], true); ?>/></td>
                    <td colspan="2">
                        Ignore traffic from blog administrators.<br/>
                        <strong style="color: red">Note:</strong> Do <strong>not</strong> enable this option if you are using WP-Cache
                    </td>
                </tr>
                <tr>
                    <th scope="row" align="right">Page tracking method</th>
                    <td>
                        <select name="options[track_full_loc]">
                            <option value="0"<?php selected($options['track_full_loc'], 0); ?>>location.pathname</option>
                            <option value="1"<?php selected($options['track_full_loc'], 1); ?>>location.href</option>
                            <option value="2"<?php selected($options['track_full_loc'], 2); ?>>location.pathname + location.search</option>
                        </select>
                    </td>
                    <td colspan="2">
                        Say, you visit <code>http://somesite.com/index.php?page=1&amp;q=something</code>.<br/>
                        With <strong>location.pathname</strong>, <code>/index.php</code> will be tracked. This is HitsLink default setting.<br/>
                        With <strong>location.href</strong>, <code>http://somesite.com/index.php?page=1&amp;q=something</code> will be tracked.<br/>
                        With <strong>location.pathname+location.search</strong>, <code>/index.php?page=1&amp;q=something</code> will be tracked.<br/>
                    </td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <?php wp_nonce_field('update-hitslink_settings'); ?>
            <input type="hidden" name="action" value="update_hitslink_settings"/>
            <input type="submit" value="Update options &raquo;"/>
        </p>
    </form>
    <p>
<?php if (empty($options['account_id'])) : ?>
        <strong style="color: red">Please enter a valid Account ID in order to use this plugin.</strong>
<?php else : ?>
        <a href="http://www.hitslink.com/account.aspx" target="_blank">Log in to your HitsLink account</a>.
<?php endif; ?>
    </p>
</div>
