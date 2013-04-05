<?php // -*- coding: utf-8 -*-
/**
 * 配列処理関数
 * @package pokefw
 * @copyright Copyright (c) 2012, Pokelabo Inc.
 * @filesource
 */

namespace pokelabo\utility;

use ArrayObject;

/**
 * 配列処理関数
 * @package pokefw
 */
class ArrayUtility {
    /**
     * 配列から値を取得する
     * @param string $key 検索キーまたは添字
     * @param array $search 配列
     * @param mixed $default_value 見つからなかった場合の値
     * @return mixed 指定したkeyが配列に設定されている場合、対応する値が返る。<br/>
     * 設定されていない場合は、default_valueの値を返す。
     * @static
     */
    public static function get($key, $search, $default_value = null) {
        if (is_array($search) || $search instanceof ArrayObject) {
            if (array_key_exists($key, $search)) {
                return $search[$key];
            }
        }
        return $default_value;
    }

    /**
     * 配列から値を取得する
     * @param string $path 設定パス(ピリオド'.'区切り)
     * @param array $search 配列
     * @param mixed $default_value 見つからなかった場合の値
     * @return mixed 指定したpathで配列をたどれた場合、対応する値が返る。<br/>
     * たどれなかった場合は、default_valueの値を返す。
     * @static
     */
    public static function dig($path, $search, $default_value = null) {
        if (is_int($path)) {
            $path = "$path";
        }
        if ($search instanceof ArrayObject) {
            $search = $search->getArrayCopy();
        }

        if (!is_array($search) || !is_string($path)) {
            return $default_value;
        }

        $element_list = explode('.', $path);
        if (empty($element_list)) {
            return null;
        }

        $value = &$search;
        $counter = count($element_list);
        foreach ($element_list as $element) {
            if (!is_array($search) && !($search instanceof ArrayObject)) {
                return $default_value;
            }
            if (!array_key_exists($element, $value)) {
                return $default_value;
            }
            if (--$counter === 0) {
                return $value[$element];
            }
            $value = &$value[$element];
        }
        
        return $value;
    }

    /**
     * $path が示す $data の要素へ $value をセットする
     * @param array $data 配列
     * @param string $path 設定パス(ピリオド'.'区切り)
     * @param mixed $value 設定値
     * @return boolean （いまんとこ）常に true
     */
    public static function bury(&$data, $path, $value = null) {
        if (!is_array($path)) {
            $path = array($path => $value);
        }

        foreach ($path as $name => $value) {
            $pointer = &$data;
            foreach (explode('.', $name) as $key) {
                $pointer = &$pointer[$key];
            }
            $pointer = $value;
            unset($pointer);
        }

        return true;
    }

    /**
     * 配列内にキーが存在するか判定する
     * @param string $path 設定パス(ピリオド'.'区切り)
     * @param array $search 配列
     * @return 存在する場合はtrue
     * @static
     */
    public static function has($path, $search) {
        if (is_int($path)) {
            $path = "$path";
        }
        if (!is_array($search) && !($search instanceof ArrayObject)) {
            return false;
        }
        if (!is_string($path)) {
            return false;
        }

        $element_list = explode('.', $path);
        if (empty($element_list)) {
            return null;
        }

        $value = &$search;
        $counter = count($element_list);
        foreach ($element_list as $element) {
            if (!is_array($search) && !($search instanceof ArrayObject)) {
                return false;
            }
            if (!array_key_exists($element, $value)) {
                return false;
            }
            if (--$counter === 0) {
                return true;
            }
            $value = &$value[$element];
        }
        
        return true;
    }

    /**
     * 配列から指定されたキーの要素のみ取り出す
     * @param array $search 配列
     * @param array $key_or_key_list 取り出すキーを格納した配列
     * @return array 取り出した結果の配列
     * @example
     * <code>
     * $search = array('type' => 'Desktop', 'price' => 24800, 'model' => 'PC98');
     * $result = ArrayUtility::filterKeys($search, array('type', 'model', 'quantity'));
     * // -> array('type' => 'Desktop', 'model' => 'PC98')
     * </code>
     */
    public static function filterByKeyList($search, $key_or_key_list) {
        if (!is_array($search)) {
            return $search;
        }

        $result = array();

        if (is_scalar($key_or_key_list)) {
            if (array_key_exists($key_or_key_list, $search)) {
                $result[$key_or_key_list] = $search[$key_or_key_list];
            }
        } else if (is_array($key_or_key_list)) {
            foreach ($key_or_key_list as $key) {
                if (array_key_exists($key, $search)) {
                    $result[$key] = $search[$key];
                }
            }
        }

        return $result;
    }
}
