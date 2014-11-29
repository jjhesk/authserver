<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年11月17日
 * Time: 下午6:08
 */
class app_user_claim
{
    private $system_log;

    public function __construct()
    {
        global $system_script_manager, $current_user;
        $this->system_log = new adminapp(
            array(
               // 'role' => 'appuser',
                'type' => 'main',
                'icon' => INNO_IMAGE_DIR . "system_log_icon.png",
                'position' => 9,
                'parent_id' => 'webcf',
                'cap' => 'web_claim',
               // 'cap' => 'manage_options',
                'title' => __('Claim Online', HKM_LANGUAGE_PACK),
                'name' => __('Online Reg', HKM_LANGUAGE_PACK),
                'cb' => array(__CLASS__, 'render_user_page'),
                'script' => 'handle_claim_fifo',
                'style' => array('adminsupportcss', 'datatable', 'dashicons',
                    'smoothness', 'datepicker_ui', 'datepicker_structure', 'datepicker_theme'),
                //--- get_environoment_config
          /*      'script_localize' =>
                    array(
                        "setting_ob",
                        array(
                            "url" => DOMAIN_API,
                            "role" => $current_user->roles[0],
                        )
                    )*/
            )
        );

    }

    public static function render_user_page()
    {
        echo get_oc_template("user_page_claim_list");
    }

} 