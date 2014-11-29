<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年11月12日
 * Time: 下午4:53
 */
class messagebox
{
    protected $titan, $must_ache, $lang, $template_keys;

    public function __construct()
    {
        $this->titan = TitanFramework::getInstance('vcoinset');
        $this->must_ache = new Mustache_Engine;
        if (isset($_REQUEST["lang"])) {
            if ($_REQUEST["lang"] == "zh") {
                $this->lang = "zh";
            } elseif ($_REQUEST["lang"] == "en") {
                $this->lang = "en";
            } elseif ($_REQUEST["lang"] == "zh-hant") {
                $this->lang = "zh";
            } elseif ($_REQUEST["lang"] == "ja") {
                $this->lang = "ja";
            } else $this->lang = "en";
        } else  $this->lang = "en";
    }

    private function supportedLanguages()
    {
        return $this->lang == "zh" || $this->lang == "en" || $this->lang == "ja";
    }

    public function __destruct()
    {
        $this->titan = NULL;
        $this->must_ache = NULL;
    }

    public function text_template($template_name, $keys)
    {
        $m = $this->titan->getOption($template_name);
        return $this->must_ache->render($m, $keys);
    }

    public static function throwError($message, $error_code)
    {
        $instance = new self();
        throw new Exception($instance->translation_code($message, $error_code), $error_code);
    }

    public static function translateError($message, $error_code)
    {
        $instance = new self();
        return $instance->translation_code($message, $error_code);
    }

    public static function getEmailTemplate($template_name, $keys)
    {
        $instance = new self();
        $text = $instance->text_template($template_name, $keys);
        $instance = NULL;
        return $text;
    }

    public function setKeys($k)
    {
        $this->template_keys = $k;
    }

    public static function successMessage($code, $output_keys)
    {
        $instance = new self();
        $instance->setKeys($output_keys);
        return $instance->translate_by_code_only($code);
    }

    private function by_key_template($key)
    {
        $template = $this->titan->getOption($key);
        return $this->must_ache->render($template, $this->template_keys);
    }


    private function by_key_template_lang($key)
    {
        if ($this->supportedLanguages()) {
            return $this->by_key_template($key . "_" . $this->lang);
        } else return "";
    }

    public function translate_by_code_only($code)
    {
        $e = "";

        switch ((int)$code) {
            case 77005:
                $e = $this->by_key_template_lang("success_reward_note");
                break;
            case 77006:
                $e = $this->by_key_template_lang("success_coupon_note");
                break;
            case 77007:
                $e = $this->by_key_template_lang("pickup_reward_note");
                break;
            case 77008:
                $e = $this->by_key_template_lang("pickup_reward_note");
                break;
            case 77009:
                $e = $this->by_key_template_lang("gain_coin");
                break;
            case 77010:
                $e = $this->by_key_template_lang("rcprocedure");
                break;
            case 77011:
                $e = $this->by_key_template_lang("rdprocedure");
                break;
        }


        return $e;
    }

    public function translation_code($message, $code)
    {
        return hardcodemessages::translation_code($message, $code, $this->lang);
    }

} 