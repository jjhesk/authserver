<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年9月10日
 * Time: 上午10:05
 */
class missionList
{
    protected $unfinished_list, $finished_list, $completed;
    protected $based_on_mission_id, $mission_list;
    protected $user, $main_api_query, $query_user_id;
    protected $mission_type = array(
        "One Time" => -1,
        "Daily Mission" => 24,
        "Weekly Mission" => 168,
    );

    public function __construct()
    {
        global $wpdb;
        $this->completed = false;
        $this->table = $wpdb->prefix . "action_reward";
    }

    public function putQuery($query)
    {
        $this->main_api_query = $query;
    }

    /**
     * input: user Id
     *  output : a list of completed action that has been done in the history
     *  by date desc
     *  1 - unlimited
     *
     *
     * action name | done time
     *
     * @param $reward_action_id
     * @return mixed
     */
    public function get_action_list($reward_action_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . "action_reward";
        $dl = $wpdb->prepare("SELECT * FROM $table WHERE action=%d", $reward_action_id);
        return $wpdb->get_results($dl);
    }

    /**
     * @param $reward_action_id
     * @return array
     */
    private function get_mission_detail($reward_action_id)
    {
        $occurence = get_post_meta($reward_action_id, "occurrence", true);
        $cycle_reward = get_post_meta($reward_action_id, "cycle_reward", true);
        $validation_times = get_post_meta($reward_action_id, "delta_f", true);
        $vcion_payer = get_post_meta($reward_action_id, "sdk_app_key", true);
        $reward_coins = get_post_meta($reward_action_id, "reward_coins", true);
        return get_defined_vars();
    }

    /**
     * @param $field_message
     * @param $context
     * @return string
     */
    private function template($field_message, $context)
    {
        $m = new Mustache_Engine;
        $new_content = $m->render($field_message, $context);
        // $field_message = $new_content;
        return $new_content;
    }

    /**
     * @param $reward_action_id
     * @param $content
     * @param string $lang
     * @return string
     */
    private function get_mission_description($reward_action_id, $content, $lang = "en")
    {

        $desc = get_post_meta($reward_action_id, "description_" . $lang, true);
        $cycle_reward = get_post_meta($reward_action_id, "cycle_reward", true);
        $total_count = get_post_meta($reward_action_id, "delta_f", true);
        list($a, $b) = $this->get_time_range($cycle_reward);
        return $this->template($desc, array(
            "times" => $total_count,
            "interval_mark" => "from $a to $b",
            "outof" => $content["reward_count"] . " out of " . $total_count,
            "today" => date('Y-m-d'),
        ));
    }

    /**
     * get the reward time range now
     * @param $cycle_reward
     * @return array
     */
    private function get_time_range($cycle_reward)
    {
        $cycle_hours_in_seconds = ($cycle_reward) * 3600;
        $start_time = 0;
        $d = new DateTime();
        $present_timestamp = $d->getTimestamp();
        /*while ($start_time < ($present_timestamp - $cycle_hours_in_seconds))
            $start_time += $cycle_hours_in_seconds;*/
        $start_time = $present_timestamp - ($present_timestamp % $cycle_hours_in_seconds);
        $next_time = $start_time + $cycle_hours_in_seconds;
        return array($start_time, $next_time);
    }

    /**
     * get the main mission from the mission list
     * @param $query_user_id
     */
    private function main_mission_list($query_user_id)
    {
        global $wpdb;

        $table = $wpdb->prefix . "action_reward";
        $template_list_all = "SELECT * FROM $table WHERE rewarded=0 AND user=%d AND mission_type=%d ORDER BY triggered DESC";
        $template_count = "SELECT COUNT(*) AS n FROM $table WHERE rewarded=0 AND user=%d AND action=%d ORDER BY triggered DESC";
        $_wp_db = new WP_Query(array(
            "post_type" => HKM_ACTION,
            "posts_per_page" => -1,
            "status" => "publish",
        ));
        $this->unfinished_list = array();
        $this->finished_list = array();
        if ($_wp_db->have_posts()) :
            while ($_wp_db->have_posts()) : $_wp_db->the_post();
                $detail = $this->get_mission_detail($_wp_db->post->ID);
                $row_id = $wpdb->prepare($template_count, $query_user_id, $_wp_db->post->ID);
                $count_times = $wpdb->get_var($row_id);
                $complete_counts = $detail["validation_times"];
                $display = $count_times . "/" . $complete_counts;
                $list_content = array(
                    "ID" => $_wp_db->post->ID,
                    "progress" => $display,
                    "reward_count" => $count_times
                );
                if (intval($count_times) < intval($complete_counts)) {
                    $this->unfinished_list[] = $list_content;
                } else {
                    $this->finished_list[] = $list_content;
                }

            endwhile;
        endif;
        wp_reset_query();
    }

    /**
     * @param $query_user_id
     * @return array
     */
    public function get_unfinished_missions($query_user_id)
    {
        $this->mission_list = array();
        $this->main_mission_list($query_user_id);
        foreach ($this->unfinished_list as $list) {
            $this->mission_list[] = array(
                "mission_id" => intval($list["ID"]),
                "description" => $this->get_mission_description($list["ID"], $list)
            );
        }

    }

    /**
     * @param $query_user_id
     * @return array
     */
    public function get_finished_missions($query_user_id)
    {
        $this->mission_list = array();
        $this->main_mission_list($query_user_id);
        foreach ($this->finished_list as $list) {
            $this->mission_list[] = $this->inLoop($list);
        }

    }

    public function get_mix_list($query_user_id)
    {
        $this->mission_list = array();
        $this->main_mission_list($query_user_id);
        $this->completed = true;
        foreach ($this->finished_list as $l) {
            $this->mission_list[] = $this->inLoop($l);
        }
        $this->completed = false;
        foreach ($this->unfinished_list as $l) {
            $this->mission_list[] = $this->inLoop($l);
        }
    }

    private function inLoop($list)
    {
        $id = $list["ID"];
        $thumb_ids = get_post_meta($id, "mission_ad", false);
        $image = wp_get_attachment_image_src($thumb_ids[0], 'large');
        return array(
            "title" => get_the_title($id),
            "mission_id" => intval($id),
            "description" => $this->get_mission_description($id, $list),
            "thumb_sq" => is_array($thumb_ids) && count($thumb_ids) > 0 ? $image[0] : "",
            "vcoin_reward" => intval(get_post_meta($id, "reward_coins", true)),
            "status" => $this->completed ? "complete" : "incomplete",
            "type" => get_post_meta($id, "display_type", true),
        );
    }

    public function getList()
    {
        if (count($this->mission_list) === 0) throw new Exception("no data found", 1019);
        if (isset($_REQUEST["unittestdev"])) {
            inno_log_db::log_vcoin_third_party_app_transaction(-1, 13922, "listing no data found");
        }
        return $this->mission_list;
    }
} 