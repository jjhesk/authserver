<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年11月20日
 * Time: 下午3:47
 */
class EmailTrigger
{
    protected $titan, $must_ache, $email_message, $template_keys, $sending_email;

    public function __construct()
    {
        $this->titan = TitanFramework::getInstance('vcoinset');
        $this->must_ache = new Mustache_Engine;
        add_action("on_redeem_coupon", array($this, "redeem_coupon"), 10, 1);
        add_action("on_redeem_reward_submission", array($this, "reward_submission"), 10, 1);
        add_action("on_reward_claim", array($this, "reward_claim"), 10, 1);
    }

    public function __destruct()
    {
        $this->titan = NULL;
        $this->must_ache = NULL;
    }

    private function combineUserTags($data)
    {

        if (isset($data->user)) {
            $user = new WP_User((int)$data->user);
            $this->sending_email = $user->user_email;
            $name_normal = $user->firstname . " " . $user->lastname;
            $data->username = trim($name_normal) == "" ? $user->user_nicename : $name_normal;
            $data->useremail = $user->user_email;
        }
        return $data;
    }

    public function text_template($template_name, $keys)
    {
        inno_log_db::log_vcoin_email(-1, 9900, print_r($keys, true));
        $m = $this->titan->getOption($template_name);
        return $this->must_ache->render($m, $keys);
    }

    public function redeem_coupon($data)
    {
        $data = $this->combineUserTags($data);
        $this->email_message = $this->text_template("email_con_0", $data);
        $this->trigger_mail($this->titan->getOption("reward_coupon_subject"));
    }

    public function reward_submission($data)
    {
        $data = $this->combineUserTags($data);
        $this->email_message = $this->text_template("email_reward_r", $data);
        $this->trigger_mail($this->titan->getOption("reward_submission_subject"));
    }

    public function reward_claim($data)
    {
        $data = $this->combineUserTags($data);
        $this->email_message = $this->text_template("email_claim_r1", $data);
        $this->trigger_mail($this->titan->getOption("reward_claim_subject"));
    }


    private function trigger_mail($subject = "vcoin email")
    {
        $headers = 'From: VcoinSys <admin@vcoinapp.com>' . "\r\n BCC:" . get_bloginfo("admin_email");
        inno_log_db::log_vcoin_email(-1, 9900, $this->email_message);
        wp_mail($this->sending_email, $subject, $this->email_message, $headers);
    }
} 