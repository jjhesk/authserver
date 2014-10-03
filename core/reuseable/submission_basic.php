<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年7月10日
 * Time: 下午5:00
 */
defined('ABSPATH') || exit;

abstract class submission_basic
{

    private static $folder_name_basemap = 'returnbasemap';
    private static $folder_name_signature = 'signatures';
    private static $folder_name_site_photo = 'work_site_description';

    abstract function uploadcpdata();

    abstract function return_image_list_submission();

    public static function graphic_print_file_new($filename)
    {
        $physical_path = self::get_rawimage_path_dir($filename);
        $physical_path_uri = self::get_rawimage_path_uri($filename);
        $frame_src = get_template_directory_uri() . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "frame.jpg";
        $legendpath = get_template_directory_uri() . DIRECTORY_SEPARATOR . "images" . DIRECTORY_SEPARATOR . "legend_webuse" . DIRECTORY_SEPARATOR . "frame.jpg";
    }

    public static function get_rawimage_path_dir($full_filename)
    {
        $uploads = wp_upload_dir();
        return $uploads['basedir'] . DIRECTORY_SEPARATOR . 'returnbasemap' . DIRECTORY_SEPARATOR . $full_filename;
    }

    public static function get_rawimage_path_uri($full_filename)
    {
        $uploads = wp_upload_dir();
        return $uploads['baseurl'] . "/returnbasemap/" . $full_filename;
    }


    /**
     * wp hook for upload_dir
     * @param $param
     * @return mixed
     */
    public static function get_custom_return_base_map_path($param)
    {
        return self::getSubPaths($param, '/' . self::$folder_name_basemap);
    }

    /**
     * wp hook for upload_dir
     * @param $path_data
     * @return mixed
     */
    public static function get_custom_return_base_map_path_with_date($path_data)
    { //sample
        return self::getSubPaths($path_data, '/' . self::$folder_name_basemap);
    }

    public static function get_custom_site_photo_path_with_date($path_data)
    {
        return self::getSubPaths($path_data, '/' . self::$folder_name_site_photo);
    }

    public static function get_custom_signature_path($path_data)
    {
        return self::getSubPaths($path_data, '/' . self::$folder_name_signature);
    }

    private static function getSubPaths($path_data, $sub_dir)
    {
        //   $uploads = wp_upload_dir();
        //  $done = print_r($path_data, true);
        //  debugoc::upload_bmap_log('path return before:' . $done, 552169);
        // $path_data['subdir'] = '/' . $sub_dir;

        // $path_data['path'] = $uploads['basedir'] . DIRECTORY_SEPARATOR . $sub_dir;
        $path_data['path'] = str_replace($path_data['subdir'], $sub_dir, $path_data['path']);
        // $path_data['url'] = $uploads['baseurl'] . '/' . $sub_dir;
        $path_data['url'] = str_replace($path_data['subdir'], $sub_dir, $path_data['url']);
        // $done = print_r($path_data, true);
        $path_data['subdir'] = str_replace($path_data['subdir'], $sub_dir, $path_data['subdir']);
        // debugoc::upload_bmap_log('path return after:' . $done, 552169);
        return $path_data;
    }

    /**
     * check the existing photo file
     * @param $filename
     * @return int
     */
    protected function check_exist_photo_file_name($filename)
    {
        global $wpdb;
        if ($filename == '') {
            debugoc::upload_bmap_log('check_exist_photo_file_name: file name is empty?! ', 552169);
        }
        // $wp_upload_dir = wp_upload_dir();
        // $image_src = $wp_upload_dir['baseurl'] . '/' . _wp_relative_upload_path($filename);
        $query_1st = "SELECT COUNT(*) FROM $wpdb->posts WHERE guid LIKE '%$filename%'";
        $query_2nd = "SELECT ID FROM $wpdb->posts WHERE guid LIKE '%$filename%'";
        $count = $wpdb->get_var($query_1st);
        //  debugoc::upload_bmap_log('check_exist_photo_file_name: image path ->:' . $filename, 552169);
        if (intval($count) > 0) {
            $attachment_id = $wpdb->get_var($query_2nd);
            debugoc::upload_bmap_log('found image and identify image location ->' . $attachment_id . ' file name: ' . $filename, 552169);
            return $attachment_id;
        } else {
            debugoc::upload_bmap_log('not found image in the folder! name: ' . $filename, 552169);
            return -1;
        }
    }

    protected function update_row_meta($upload_id, $nature, $array_ids)
    {
        global $wpdb;
        $update_query = array();
        $content_ids = implode(",", $array_ids);
        switch ($nature) {
            case 'basemap':
                $update_query = array("drawmaps" => $content_ids);
                break;
            case 'sitephoto':
                $update_query = array("photos" => $content_ids);
                break;
            case 'sign':
                $update_query = array("signatures" => $content_ids);
                break;
        }
        return $wpdb->update(DB_PROJECTRETURN, $update_query, array(
            "ID" => $upload_id
        ), array(
            "%s"
        ), array("%d"));
    }
} 