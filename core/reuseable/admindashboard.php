<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月19日
 * Time: 下午3:34
 */
// add new dashboard widgets
/*
add_action('wp_dashboard_setup', 'wptutsplus_add_dashboard_widgets');


function wptutsplus_add_dashboard_widgets()
{
    wp_add_dashboard_widget('wptutsplus_dashboard_welcome', 'Welcome', 'wptutsplus_add_welcome_widget');
    wp_add_dashboard_widget('wptutsplus_dashboard_links', 'Useful Links', 'wptutsplus_add_links_widget');
}*/

if (!class_exists('admindashboard')):
    class admindashboard
    {
        protected
            $access_role,
            $key, $title,
            $view_key_file_name,
            $js,
            $css,
            $dataparam,
            $script_localize;

        function __construct($key, $title, $for_role_key, $js = null, $css = array(), $localize = array())
        {
            $this->key = $key;
            $this->title = $title;
            $this->access_role = $for_role_key;
            if (count($localize) > 0) {
                $this->script_localize = $localize;
            }
            if (count($js) > 0) {
                $this->js = $js;
            }
            if (count($css) > 0) {
                $this->css = $css;
            }
        }

        function __destruct()
        {
            $this->access_role = NULL;
            $this->key = NULL;
            $this->title = NULL;
            $this->view_key_file_name = NULL;
            $this->js = NULL;
            $this->css = NULL;
            $this->dataparam = NULL;
            $this->script_localize = NULL;
            gc_collect_cycles();
        }

        public function setTemplate($view_key_file_name)
        {
            $this->view_key_file_name = $view_key_file_name;
        }

        public function setData(WP_User $user, $param = array())
        {
            $arra = array();
            foreach ($param as $k) {
                $arra[$k] = userBase::getVal($user->ID, $k);
            }
            $this->dataparam = $arra;
        }

        public function css_load()
        {
            foreach ($this->css as $css) {
                wp_enqueue_style($css);
            }
        }

        public function js_load()
        {
            wp_enqueue_script($this->js);
            if (isset($this->script_localize))
                wp_localize_script($this->js, $this->script_localize[0], $this->script_localize[1]);
        }

        public function init()
        {
            global $current_user;
            //access privilege

            if ($this->access_role == $current_user->roles[0]) {
                add_action('admin_enqueue_scripts', array(&$this, 'js_load'));
                add_action('admin_enqueue_scripts', array(&$this, 'css_load'));
                wp_add_dashboard_widget($this->key, $this->title, array(&$this, "render_dashboard"));
            }
        }

        public function render_dashboard()
        {
            if (isset($this->dataparam)) {
                echo get_oc_template_mustache($this->view_key_file_name, $this->dataparam);
            } else
                echo get_oc_template($this->view_key_file_name);
        }
    }
endif;