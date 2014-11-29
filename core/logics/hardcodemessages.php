<?php

/**
 * Created by PhpStorm.
 * User: Hesk
 * Date: 14年11月27日
 * Time: 上午11:49
 */
class hardcodemessages
{
    public static function translation_code($message, $code, $lang)
    {
        $e = $message;
        if ($lang == "zh") {
            switch ((int)$code) {
                case 1001:
                    $e = "imusic account not found in cn";
                    break;

                case 1002:
                    $e = "imusic account not found in cn";
                    break;

                case 1003:
                    $e = "missing keys";
                    break;

                case 1004:
                    $e = "setting is not allow";
                    break;
                case 10979:
                    $e = "the current user does not have valid vcoin account, please go back and with the settings";
                    break;
                case 1010:
                    $e = "Please login";
                    break;

                case 1601:
                    $e = "Invalid ID";
                    break;


                case 1602:
                    $e = "invalid account uuid, please go back and with the settings";
                    break;

                case 1603:
                    $e = "invalid amount";
                    break;

                case 9919:
                    $e = "url is missing";
                    break;


                case 1007:
                    $e = "module not installed";
                    break;


                case 10001:
                    $e = "missing comment id";
                    break;

                case 10002:
                    $e = "missing reference id";
                    break;

                case 10003:
                    $e = "missing comment flag";
                    break;

                case 10004:
                    $e = "missing object id";
                    break;

                case 10005:
                    $e = "missing comment content";
                    break;


                case 10006:
                    $e = "missing feature for vcoin history";
                    break;
                case 10007:
                    $e = "missing action for vcoin";
                    break;
                case 10008:
                    $e = "get history uuid is missing. Check settings on user profile";
                    break;
                case 10009:
                    $e = "missing action id";
                    break;
                case 10010:
                    $e = "appkey is missing";
                    break;
                case 10011:
                    $e = "appkey for vcoinapp not matched";
                    break;
                case 10012:
                    $e = "down_app_key is missing";
                    break;


                case 1552:
                    $e = "you have already downloaded";
                    break;

                case 1553:
                    $e = "sdk appkey is not verified";
                    break;

                case 1554:
                    $e = "you have already got the reward";
                    break;


                case 1021:
                    $e = "Not initiated";
                    break;
                case 1024:
                    $e = "stock_id is not defined";
                    break;
                case 1022:
                    $e = "reward coin nature is not defined, No Vcoin Account";
                    break;
                case 1023:
                    $e = "No Vcoin Account is found";
                    break;

                case 1011:
                    $e = "the request action Point does not exist";
                    break;

                case 1012:
                    $e = "The action point is not ready";
                    break;

                case 1013:
                    $e = "SDK App Key is not selected";
                    break;


                case 1015:
                    $e = "Occurrence is not selected";
                    break;

                case 1016:
                    $e = "Frequency should be more than 1 for both and
         repeatable simple and repeatable continuous";
                    break;


                case 1017:
                    $e = "repeated trigger is not allowed within an interval";
                    break;

                case 1014:
                    $e = "reward have been gained";
                    break;


                case 1018:
                    $e = "no reward to gain and the action is recorded.";
                    break;

                case 1031:
                    $e = "the old password is not presented.";
                    break;

                case 1032:
                    $e = "the password does not match to set the new password.";
                    break;

                case 1019:
                    $e = "no data found.";
                    break;

                case 1088:
                    $e = "user uuid is not set";
                    break;

                case 1720:
                    $e = "wasted key is not presented";
                    break;

                case 1721:
                    $e = "hash for renewal is not presented";
                    break;

                case 1722:
                    $e = "app key for renewal is not presented";
                    break;

                case 1723:
                    $e = "app nouce for renewal is not presented";
                    break;
                case 1725:
                    $e = "token invalid";
                    break;

                case 1726:
                    $e = "this token is not expired";
                    break;

                case 1727:
                    $e = "calculation is invalid";
                    break;

                case 1079:
                    $e = "user does not have valid vcoin account, please go back with the settings of the user profile";
                    break;
                case 6011:
                    $e = "unable to deduct reserved coins for the developer";
                    break;
                case 1901:
                    $e = "you are not login";
                    break;

                case 2005:
                    $e = "imusic account not found in cn";
                    break;
                case 2007:
                    $e = "credit account not found in cn";
                    break;
                case 2006:
                    $e = "debit account not found in cn";
                    break;

                case 2102:
                    $e = "not enough coin in chinese";
                    break;
                case 2010:
                    $e = "account already exist chinese";
                    break;
                case 1440:
                    $e = "This is not Vcoin App.";
                    break;

                    break;
                case 1504:
                    $e = "Token is required for authentication.";
                    break;
                case 1509:
                    $e = "App key is needed.";
                    break;
                case 1505:
                    $e = "Invalid authentication token. Use the `generate_token` Auth API method.";
                    break;
                case 1508:
                    $e = "Unmatched App Key, please go back and double check.";
                    break;
                case 1099:
                    $e = "產品已換領完畢";
                    break;
                case 1610:
                    $e = "產品已換領完畢";
                    break;
                case 77001:
                    $e = "你已成功下載 ";
                    break;
                case 77002:
                    $e = "你已賺取 VCoin";
                    break;
                case 77003:
                    $e = "你己完成";
                    break;
                case 77004:
                    $e = "你已在達到 之中 的獎賞";
                    break;

            }
        }

        if ($lang == "en") {
            switch ((int)$code) {
                case 770105:

                    break;
            }
        }

        if ($lang == "ja") {
            switch ((int)$code) {
                case 770105:

                    break;
            }
        }

        return $e;
    }
} 