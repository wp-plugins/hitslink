<?php
/*
Plugin Name: HitsLink
Description: HitsLink - Website Statistics, web analytics, hit counter
Version: 1.0
Plugin URI: http://blog.sjinks.pro/wordpress/plugins/360-hitslink-for-wordpress/
Author: Vladimir Kolesnikov
Author URI: http://blog.sjinks.pro/
*/

    class HitsLink
    {
        protected $options = null;

        /**
         * @return HitsLink
         */
        public static function instance()
        {
            static $self = false;

            if (false === $self) {
                $self = new HitsLink();
            }

            return $self;
        }

        private function __construct()
        {
            add_action('init',       array($this, 'init'));
            add_action('admin_init', array($this, 'admin_init'));
        }

        public function init()
        {
            $this->loadOptions();
            if (!empty($this->options['account_id'])) {
                $user  = wp_get_current_user();
                $admin = false;

                if (!empty($user)) {
                    $admin = $user->has_cap('administrator');
                }

                if (!$this->options['ignore_admins'] || !$admin) {
                    if (!is_admin()) {
                        add_action('wp_head', array($this, 'wp_head'), 999);
                    }
                    else {
                        add_action('admin_head', array($this, 'wp_head'), 999);
                    }
                }
            }
        }

        public function admin_init()
        {
            add_action('admin_post_update_hitslink_settings', array(&$this, 'update_hitslink_settings'));
            add_options_page('HitsLink Options', 'HitsLink', 'manage_options', 'hitslink/options-hitslink.php');
        }

        public function update_hitslink_settings()
        {
            $options = $this->options;

            if (isset($_POST['options'])) {
                check_admin_referer('update-hitslink_settings');

                foreach ($options as $key => $value) {
                    if (isset($_POST['options'][$key])) {
                        $options[$key] = $_POST['options'][$key];
                    }
                    else {
                        $options[$key] = null;
                    }
                }

                update_option('sj_hitslinks_settings', $options);
            }

            wp_safe_redirect(admin_url('options-general.php?page=hitslink/options-hitslink.php&updated=1'));
            die();
        }

        public function wp_head()
        {
            $code = $this->getCode();
            print "<!-- HitsLink Plugin -->\n"  . $code . "\n<!-- /HitsLink Plugin -->";
        }

        public function& getOptions()
        {
            return $this->options;
        }

        protected function loadOptions()
        {
            $defaults = array(
                'account_id'     => '',
                'location'       => 0,
                'ignore_admins'  => 0,
                'track_full_loc' => 0,
            );

            $update  = false;
            $options = get_option('sj_hitslinks_settings');

            if (is_array($options)) {
                foreach ($defaults as $key => $value) {
                    if (!isset($options[$key])) {
                        $options[$key] = $value;
                        $update = true;
                    }
                }

                foreach ($options as $key => $value) {
                    if (!isset($defaults[$key])) {
                        unset($options[$key]);
                        $update = true;
                    }
                }
            }
            else {
                $options = $defaults;
                $update  = true;
            }

            if ($update) {
                update_option('sj_hitslinks_settings', $options);
            }

            $this->options = $options;
        }

        protected function getCode()
        {
            $account  = $this->options['account_id'];
            $location = $this->options['location'];

            switch ($this->options['track_full_loc']) {
                case 1:  $page = 'location.href'; break;
                case 2:  $page = 'location.pathname + location.search'; break;
                default: $page = 'location.pathname'; break;
            }

            $code = <<< delimiter
<!-- www.hitslink.com web tools statistics hit counter code -->
<script type="text/javascript" id="wa_u"></script>
<script type="text/javascript">/*<![CDATA[*/
wa_account="{$account}";wa_location={$location};wa_pageName={$page};document.cookie='__support_check=1';wa_hp='http';wa_rf=document.referrer;wa_sr=window.location.search;wa_tz=new Date();if(location.href.substr(0,6).toLowerCase()=='https:')wa_hp='https';wa_data='&an='+escape(navigator.appName)+'&sr='+escape(wa_sr)+'&ck='+document.cookie.length+'&rf='+escape(wa_rf)+'&sl='+escape(navigator.systemLanguage)+'&av='+escape(navigator.appVersion)+'&l='+escape(navigator.language)+'&pf='+escape(navigator.platform)+'&pg='+escape(wa_pageName);wa_data=wa_data+'&cd='+screen.colorDepth+'&rs='+escape(screen.width+ ' x '+screen.height)+'&tz='+wa_tz.getTimezoneOffset()+'&je='+ navigator.javaEnabled();wa_img=new Image();wa_img.src=wa_hp+'://counter.hitslink.com/statistics.asp'+'?v=1&s='+wa_location+'&eacct='+wa_account+wa_data+'&tks='+wa_tz.getTime();document.getElementById('wa_u').src=wa_hp+'://counter.hitslink.com/track.js';
/*]]>*/
</script>
<!-- End www.hitslink.com statistics web tools hit counter code -->
delimiter;

            return $code;
        }
    }

    if (!defined('DOING_CRON') && !defined('DOING_AJAX')) {
        HitsLink::instance();
    }
?>