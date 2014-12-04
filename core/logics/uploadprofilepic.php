<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年12月3日
 * Time: 下午7:00
 */
class uploadProfilePic extends submission_basic
{
    private $post_title, $submission_nature, $user;


    function __construct()
    {
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }
    }

    /**
     * @param $file
     * @return mixed
     */
    public function _pre_upload($file)
    {
        add_filter('upload_dir', array('submission_basic', 'get_custom_signature_path'));
        return $file;
    }

    /**
     * @param $file_info
     * @return mixed
     */
    public function _post_upload($file_info)
    {
        remove_filter('upload_dir', array('submission_basic', 'get_custom_return_base_map_path'));
        return $file_info;
    }

    /**
     * @param $user
     * @return string
     */
    public static function uploadProfile($user)
    {
        $up = new self();
        $up->setUser($user);
        return $up->uploadcpdata();
    }

    public function setUser(WP_User $user)
    {
        $this->user = $user;
    }

    /**
     * update action media handle with File Name and the Post ID
     * @param $file
     * @param $post_id
     * @return mixed
     */
    private function action_media_handle($file, $post_id)
    {
        add_filter('wp_handle_upload_prefilter', array($this, '_pre_upload'));
        add_filter('wp_handle_upload', array($this, '_post_upload'));
        $attach_id = media_handle_upload($file, $post_id);
        return $attach_id;
    }

    /**
     * @throws Exception
     * @internal param \WP_user $user
     * @return string
     */
    function uploadcpdata()
    {
        try {
            $attachments = array();
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            /*        $dumpinfo = print_r($finfo, true);
               $dumprequest = print_r($_REQUEST, true);
               $dumpfile = print_r($_FILES, true);*/
            if (count($_FILES) == 0) {
                throw new Exception("files not detected. array is [] ", 4091);
            } else {
                if ($_FILES) {
                    foreach ($_FILES as $file => $array) {
                        if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                            $fileerrormessage = $_FILES[$file]['error'];
                            continue;
                        }
                        if (false === $ext = array_search(
                                $finfo->file($_FILES[$file]['tmp_name']),
                                array(
                                    'jpg' => 'image/jpeg'
                                    //'png' => 'image/png',
                                    //'gif' => 'image/gif',
                                ), true)
                        ) {
                            throw new RuntimeException('Invalid file format.');
                        }
                        $attach_result_id = $this->action_media_handle($file, 0);

                        //   $attach_result_id = $this->check_exist_photo_file_name($array['name']);
                        /*  if ($attach_result_id === -1) {
                              if ($post_id > 0) {
                                  $attach_result_id = $this->action_media_handle($file, $post_id);
                              }
                              if (is_wp_error($attach_result_id)) {
                                  throw new Exception($attach_result_id->get_error_message(), 4091);
                              } else {
                                  $this->post_title = "profile picture";
                                  $this->update_attachment_postmeta($attach_result_id);
                              }
                          }*/

                        if (is_wp_error($attach_result_id)) {
                            throw new Exception($attach_result_id->get_error_message(), 4091);
                        } else update_user_meta($this->user->ID, "profile_picture_uploaded", $attach_result_id);

                        array_push($attachments, $attach_result_id);
                    }
                    // debugoc::upload_bmap_log('submission_nature start.' . $this->submission_nature, 552169);
                    // $this->update_row_meta($this->uploaded_return_id, $this->submission_nature, $attachments);
                }
            }
            return "upload complete";
        } catch (Exception $e) {
            throw $e;
        }
    }

    function return_image_list_submission()
    {

    }
}