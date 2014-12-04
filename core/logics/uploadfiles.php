<?php
/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年7月10日
 * Time: 下午4:59
 */
defined('ABSPATH') || exit;

class uploadfiles extends submission_basic
{


    //enum  machine_data
    //      basemap
    //      sitephoto
    //      sign
    private $uploaded_return_id, $job_id, $post_title, $post_content, $submission_nature, $post_content2, $user;


    function setUser(WP_User $upload_image_user)
    {
        $this->user = $upload_image_user;
    }

    /**
     * @param $based_on_upload_id
     * @param $jobid
     * @param $submission_nature
     */
    function __construct($based_on_upload_id = -1, $jobid = -1, $submission_nature)
    {
        if ($based_on_upload_id > -1) {
            $this->uploaded_return_id = $based_on_upload_id;
            $this->job_id = $jobid;
            $this->submission_nature = $submission_nature;
        }
    }

    /**
     * implement upload process
     * @return int
     */
    function uploadcpdata()
    {
        // TODO: Implement uploadcpdata() method.
        /*
                        $data = $_POST["datablock"];
                          print_r($_POST);
                          echo "this is the data response from this end point now";
                           return $_POST;
                         $rawInput = fopen('php://input', 'r');
                          $tempStream = fopen('php://temp', 'r+');
                          stream_copy_to_stream($rawInput, $tempStream);
                          rewind($tempStream);
        */

        global $wpdb;
        $raw = file_get_contents('php://input');
        $json_array = json_decode($raw);
        $post_job_id = $json_array->post_id;
        $machine_id = $json_array->device_id;
        $format = array('%s', '%d', '%s');
        $entry = array('machine_data' => $raw, 'post_job_id' => $post_job_id, 'android_id' => $machine_id);
        $result_insert = $wpdb->insert(DB_PROJECTRETURN, $entry, $format);
        //  $out = $result_insert == null ? -1 : $wpdb->insert_id;

        return $result_insert == null ? -1 : $wpdb->insert_id;
    }

    /**
     * @throws Exception
     */
    function photo_profile_submission()
    {
        try {
            $this->post_title = "profile picture";
            $this->post_content = "";
            $this->control_media_upload($this->job_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    function photo_submission()
    {
        try {
            $this->post_title = "work site photo submission";
            $this->post_content = "";
            $this->control_media_upload($this->job_id);
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @return string
     * @throws Exception
     */
    function return_image_list_submission()
    {
        try {

            if (intval($this->job_id) > 0) {
                return $this->control_media_upload($this->job_id);
            } else {
                //   debugoc::upload_bmap_log('error! target job ID is not set', 552169);
                throw new Exception('error! target job ID is not set', 5584);
            }
        } catch (Exception $e) {
            throw $e;
        }
    }

    /**
     * @param $post_id
     * @return string
     * @throws RuntimeException
     * @throws Exception
     */

    private function control_media_upload($post_id = 0)
    {
        if (!function_exists('wp_generate_attachment_metadata')) {
            require_once(ABSPATH . "wp-admin" . '/includes/image.php');
            require_once(ABSPATH . "wp-admin" . '/includes/file.php');
            require_once(ABSPATH . "wp-admin" . '/includes/media.php');
        }

        try {
            $attachments = array();
            // debugoc::upload_bmap_log('wp_generate_attachment_metadata start.', 552169);

            /**
             * mime type data checking
             */
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            /*        $dumpinfo = print_r($finfo, true);
               $dumprequest = print_r($_REQUEST, true);
               $dumpfile = print_r($_FILES, true);*/

            //    debugoc::upload_bmap_log("MIME TYPE INFO" . $dumpinfo . " file name:" . $dumpfile . "dump request:" . $dumprequest, 552169);
            if (count($_FILES) == 0) {
                throw new Exception("files not detected. array is [] ", 4091);
            } else {
                if ($_FILES) {
                    //   debugoc::upload_bmap_log('$_FILES start.', 552169);
                    foreach ($_FILES as $file => $array) {
                        if ($_FILES[$file]['error'] !== UPLOAD_ERR_OK) {
                            $fileerrormessage = $_FILES[$file]['error'];
                            //  debugoc::upload_bmap_log('upload error. message: ' . $fileerrormessage . '. the next file process will continue.', 552169);
                            continue;
                        }

                        if (false === $ext = array_search(
                                $finfo->file($_FILES[$file]['tmp_name']),
                                array(
                                    'jpg' => 'image/jpeg',
                                    'png' => 'image/png',
                                    'gif' => 'image/gif',
                                ),
                                true
                            )
                        ) {
                            throw new RuntimeException('Invalid file format.');
                        }
                        $attach_result_id = $this->check_exist_photo_file_name($array['name']);
                        if ($attach_result_id === -1) {
                            if ($post_id > 0) {
                                $attach_result_id = $this->action_media_handle($file, $post_id);
                            }
                            if (is_wp_error($attach_result_id)) {
                                throw new Exception($attach_result_id->get_error_message(), 4091);
                            } else {
                                switch ($this->submission_nature) {
                                    case 'basemap':
                                        $this->post_title = "return base map submission";
                                        $this->post_content = $this->name_to_legend_array($array['name']);
                                        $this->post_content2 = $array['name'];
                                        break;
                                    case 'sitephoto':
                                        $this->post_title = "site photo submission";
                                        $this->post_content = "";
                                        $this->post_content2 = "";
                                        break;
                                    case 'sign':
                                        $this->post_title = "signature from the work site";
                                        $this->post_content = "";
                                        $this->post_content2 = "reference Job ID:" . $post_id;
                                        break;
                                }
                                $this->update_attachment_postmeta($attach_result_id);
                            }
                        }
                        array_push($attachments, $attach_result_id);
                    }
                    // debugoc::upload_bmap_log('submission_nature start.' . $this->submission_nature, 552169);
                    $this->update_row_meta($this->uploaded_return_id, $this->submission_nature, $attachments);
                }
            }
            return "upload complete";
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function name_to_legend_array($str)
    {
        $parts = strstr($str, '_');
        if ($parts != '') {
            $t = substr($parts, 1);
            $t2 = str_replace(".png", "", $t);
            $tt = explode("_", $t2);
            if (count($tt) > 0) {
                return implode(",", $tt);
            }
        } else return $str;
    }

    /**
     * @param $file
     * @return mixed
     */
    public function _pre_upload($file)
    {
        switch ($this->submission_nature) {
            case 'basemap':
                add_filter('upload_dir', array('submission_basic', 'get_custom_return_base_map_path'));
                break;
            case 'sitephoto':
                add_filter('upload_dir', array('submission_basic', 'get_custom_site_photo_path_with_date'));
                break;
            case 'sign':
                add_filter('upload_dir', array('submission_basic', 'get_custom_signature_path'));
                break;
        }
        return $file;
    }

    /**
     * @param $file_info
     * @return mixed
     */
    public function _post_upload($file_info)
    {
        switch ($this->submission_nature) {
            case 'basemap':
                remove_filter('upload_dir', array('submission_basic', 'get_custom_return_base_map_path'));
                break;
            case 'sitephoto':
                remove_filter('upload_dir', array('submission_basic', 'get_custom_site_photo_path_with_date'));
                break;
            case 'sign':
                remove_filter('upload_dir', array('submission_basic', 'get_custom_signature_path'));
                break;
        }
        return $file_info;
    }

    /**
     * update action media handle with File Name and the Post ID
     * @param $file
     * @param $post_id
     * @return mixed
     */
    private function action_media_handle($file, $post_id)
    {
        add_filter('wp_handle_upload_prefilter', array(&$this, '_pre_upload'));
        add_filter('wp_handle_upload', array(&$this, '_post_upload'));
        $attach_id = media_handle_upload($file, $post_id);
        return $attach_id;
    }

    /**
     * update attachment with the given ID
     * @param $post_id
     */
    private function update_attachment_postmeta($post_id)
    {
        //  global $wpdb;
        $args = array(
            "ID" => $post_id,
            //the type of the upload photo
            "post_title" => $this->post_title,
            //description
            "post_content" => $this->post_content,
            //post status
            "post_status" => "publish",
            //post ID for the previous uploaded raw data
            "post_excerpt" => $this->post_content2
        );
        /*
                $Keys [] = $NewImage->ID;
                $Captions [] = $NewImage->post_excerpt;
                $Descriptions [] = $NewImage->post_content;*/
        wp_update_post($args);
    }
}