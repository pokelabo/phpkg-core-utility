<?php
/**
 * 日付処理関数
 * @package pokefw
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\utility;

/**
 * 配列処理関数
 * @package pokefw
 */
class DateUtility {
    /**
     * 現在時刻をDB形式文字列で取得する(yyyy-mm-dd HH:MM:SS)
     * @return string 現在時刻(DB形式文字列)
     * @static
     */
    public static function now() {
        return date('Y-m-d H:i:s');
    }

    /**
     * 今日の日付をDB形式文字列で取得する(yyyy-mm-dd)
     * @return string 今日の日付(DB形式文字列)
     * @static
     */
    public static function today() {
        return date('Y-m-d');
    }
}
