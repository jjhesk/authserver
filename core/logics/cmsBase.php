<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年8月12日
 * Time: 下午3:42
 */
defined('ABSPATH') || exit;

abstract class cmsBase
{
    protected $panel_metabox;

    public function __construct()
    {
    }

    abstract protected function add_tab();

    abstract public static function addRWMetabox($meta_boxes);

    abstract protected function addAdminSupportMetabox();

    protected static function withUpdateFieldN($postid, $name_field, $val)
    {
        if (isset($_POST[$name_field]))
            update_post_meta($postid, $name_field, $val, get_post_meta($postid, $name_field, true));
    }
}
