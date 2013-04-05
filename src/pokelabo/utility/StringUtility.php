<?php
/**
 * 文字列処理関数
 * @package pokefw
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\utility;

/**
 * 文字列処理関数
 * @package pokefw
 */
class StringUtility {
    /**
     * 先頭文字を大文字にする
     * @var boolean
     */
    const CAMELCASE_UCFIRST = true;
    /**
     * 先頭文字を小文字にする
     * @var boolean
     */
    const CAMELCASE_LCFIRST = false;

    /**
     * CamelCaseブロックをsnake_caseブロックに変換する。<br/>
     * $str には単一ブロックを指定すること。<br/>
     * OK: MyPage<br/>
     * NG: MyPage/GiftItems
     * @param string $str CamelCaseブロック。
     * @return string snake_case化された文字列。
     * @static
     */
    public static function toSnake($str) {
        if (!is_string($str)) return $str;
    
        $is_first = true;
        $callback = function($m) use(&$is_first) {
            $len = strlen($m[1]);
            $s = strtolower($m[1]);
            if ($len === 1) { // 先頭一文字のみ大文字
                if ($is_first) {
                    $is_first = false;
                    if ($m[2] === null) {
                        return $s;
                    } else {
                        return $s . $m[2];
                    }
                } else {
                    if ($m[2] === null) {
                        return '_' . $s;
                    } else {
                        return '_' . $s . $m[2];
                    }
                }
            }

            // 大文字が連続している

            if ($m[2] === '') {   // 最後まで大文字
                if ($is_first) {
                    $is_first = false;
                    return $s;
                } else {
                    return '_' . $s;
                }
            } else {                // 小文字・数字も続く
                if ($is_first) {
                    $is_first = false;
                    return substr($s, 0, $len - 1) . '_' . $s[$len - 1] . $m[2];
                } else {
                    return '_' . substr($s, 0, $len - 1) . '_' . $s[$len - 1] . $m[2];
                }
            }
        };

        return preg_replace_callback('/([A-Z]+)([0-9a-z]*)/', $callback, ucfirst($str));
    }

    /**
     * snake_caseブロックをCamelCaseブロックに変換する。<br/>
     * $str には単一ブロックを指定すること。<br/>
     * OK: my_page<br/>
     * NG: my_page/gift_items
     * @param string $str snake_caseブロック。
     * @param boolean $ucfirst 先頭文字を大文字にするか、小文字にするか。<br/>
     *                         StringUtility::CAMELCASE_UCFIRST: 大文字(default)<br/>
     *                         StringUtility::CAMELCASE_LCFIRST: 小文字
     * @return string CamelCase化された文字列。
     * @static
     */
    public static function toCamel($str, $ucfirst = self::CAMELCASE_UCFIRST) {
        if (!is_string($str)) return $str;
    
        $is_first = true;
        $callback = function($m) use($ucfirst, &$is_first) {
            $s = strtolower($m[1]);
            if ($is_first) {
                $is_first = false;
                return $ucfirst ? ucfirst($s) : $s;
            } else {
                return ucfirst($s);
            }
        };

        return preg_replace_callback('/([[:alnum:]]+)(_*)/', $callback, $str);
    }
}
