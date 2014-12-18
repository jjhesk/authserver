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
                    $e = "imusic帳號暫未發現";
                    break;

                case 1002:
                    $e = "imusic帳號暫未發現";
                    break;

                case 1003:
                    $e = "遺失鍵";
                    break;

                case 1004:
                    $e = "設置不許可";
                    break;
                case 10979:
                    $e = "現時用戶並沒有有效的VCoin帳戶，請返回及設置";
                    break;
                case 1010:
                    $e = "請登入";
                    break;

                case 1601:
                    $e = "ID無效";
                    break;


                case 1602:
                    $e = "用戶無效，請返回及設置";
                    break;

                case 1603:
                    $e = "用戶無效";
                    break;

                case 9919:
                    $e = "遺失網址";
                    break;


                case 1007:
                    $e = "未安裝module";
                    break;


                case 10001:
                    $e = "遺失comment id";
                    break;

                case 10002:
                    $e = "遺失reference id";
                    break;

                case 10003:
                    $e = "遺失comment flag";
                    break;

                case 10004:
                    $e = "遺失object id";
                    break;

                case 10005:
                    $e = "遺失留言內容";
                    break;


                case 10006:
                    $e = "遺失VCoin紀錄";
                    break;
                case 10007:
                    $e = "遺失VCoin操作";
                    break;
                case 10008:
                    $e = "遺失get history uuid,請檢查帳戶設定頁面";
                    break;
                case 10009:
                    $e = "遺失操作 id";
                    break;
                case 10010:
                    $e = "遺失程式鍵";
                    break;
                case 10011:
                    $e = "VCoinApp程式鍵不匹配";
                    break;
                case 10012:
                    $e = "遺失下載的程式鍵";
                    break;


                case 1552:
                    $e = "你已下載";
                    break;

                case 1553:
                    $e = "未認證SDK程式鍵";
                    break;

                case 1554:
                    $e = "你已取得獎賞";
                    break;


                case 1021:
                    $e = "未能啟動";
                    break;
                case 1024:
                    $e = "STOCK_ID沒有定義";
                    break;
                case 1022:
                    $e = "獎勵金幣性質沒有定義，沒有VCOIN帳戶";
                    break;
                case 1023:
                    $e = "未能發現VCoin帳號";
                    break;

                case 1011:
                    $e = "要求的動作位置不存在";
                    break;

                case 1012:
                    $e = "動作位置尚未準備";
                    break;

                case 1013:
                    $e = "SDK程式鍵尚未選擇";
                    break;


                case 1015:
                    $e = "Occurrence尚未選擇";
                    break;

                case 1016:
                    $e = "簡單重複和持續重複的發生率應該多於一次";
                    break;


                case 1017:
                    $e = "指定時間內不能重複";
                    break;

                case 1014:
                    $e = "已獲取獎賞";
                    break;


                case 1018:
                    $e = "沒有獲取獎賞及動作已被記錄.";
                    break;

                case 1031:
                    $e = "舊的密碼已不存在.";
                    break;

                case 1032:
                    $e = "密碼不正確，未能設定新密碼.";
                    break;

                case 1019:
                    $e = "未有發現資料.";
                    break;

                case 1088:
                    $e = "用戶UUID未被設定";
                    break;

                case 1720:
                    $e = "沒有提交wasted key ";
                    break;

                case 1721:
                    $e = "沒有提交hash for renewal";
                    break;

                case 1722:
                    $e = "沒有提交app key for renewal";
                    break;

                case 1723:
                    $e = "沒有提交app nouce for renewal";
                    break;
                case 1725:
                    $e = "代幣無效";
                    break;

                case 1726:
                    $e = "此金幣已過期";
                    break;

                case 1727:
                    $e = "計算失效";
                    break;

                case 1079:
                    $e = "用戶未有有效的VCoin帳號，請返回及設定用戶個人資料";
                    break;
                case 6011:
                    $e = "無法扣除開發人員保留的金幣";
                    break;
                case 1901:
                    $e = "你尚未登入";
                    break;

                case 2005:
                    $e = "imusic帳號暫未發現";
                    break;
                case 2007:
                    $e = "信用帳戶暫未發現";
                    break;
                case 2006:
                    $e = "扣款賬戶暫未發現";
                    break;

                case 2102:
                    $e = "沒有足夠金幣";
                    break;
                case 2010:
                    $e = "帳號已經存在";
                    break;
                case 1440:
                    $e = "這不是VCoin應用程式.";
                    break;

                    break;
                case 1504:
                    $e = "代幣需要進行驗證。";
                    break;
                case 1509:
                    $e = "需要程式鍵.";
                    break;
                case 1505:
                    $e = "代幣驗證無效。請使用`generate_token`API方法驗證。";
                    break;
                case 1508:
                    $e = "程式鍵不配合，請返回並仔細檢查。";
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
                case 1781:
                    $e = "你的nonce值不正確，請使用'get_nonce'方法n: from nonce input:";
                    break;
                case 1782:
                    $e = "在你的請求中，必須包含用戶名稱或電郵";
                    break;
                case 1783:
                    $e = "在你的請求中，必須包含密碼";
                    break;
               /* case 1784:
                    $e = "密碼安全性不足。請確保密碼1）必須包含至少一個數字，2）必須包含至少一個大寫字符，3）必須包含至少一個特殊符號";
                    break;*/
              /*  case 1034:
                    $e = "你已在達到 之中 的獎賞";
                    break;*/

              /*  case 1031:
                    $e = "舊密碼未被提交";
                    break;
                case 1032:
                    $e = "你的舊密碼不一致";
                    break;*/
            }
        }
/*
        if ($lang == "en") {
            switch ((int)$code) {
                case 770105:

                    break;
            }
        }*/

        if ($lang == "ja") {
            switch ((int)$code) {
                case 770105:

                    break;
            }
        }

        return $e;
    }
} 