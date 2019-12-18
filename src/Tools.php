<?php

namespace fearless\Tools;


class Tools {

    public static function getDate($timestamp) {
        $now = time();
        $diff = $now - $timestamp;
        if ($diff <= 60) {
            return $diff . '秒前';
        } elseif ($diff <= 3600) {
            return floor($diff / 60) . '分钟前';
        } elseif ($diff <= 86400) {
            return floor($diff / 3600) . '小时前';
        } elseif ($diff <= 2592000) {
            return floor($diff / 86400) . '天前';
        } else {
            return '一个月前';
        }
    }

    /**
     * 将查询的二维对象转换成二维数组
     * @param array $res
     * @param string $key 允许指定索引值
     * @return array
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     */
    public static function buildArrFromObj($res, $key = '') {
        $arr = [];
        foreach ($res as $value) {
            $value = $value->toArray();
            if ($key) {
                $arr[$value[$key]] = $value;
            } else {
                $arr[] = $value;
            }
        }

        return $arr;
    }

    /**
     * 将二维数组变成指定key
     * @param $array
     * @param $keyName
     * @author zhaoxiang <zhaoxiang051405@gmail.com>
     * @return array
     */
    public static function buildArrByNewKey($array, $keyName = 'id') {
        $list = array();
        foreach ($array as $item) {
            $list[$item[$keyName]] = $item;
        }
        return $list;
    }

    /**
     * 把返回的数据集转换成Tree
     * @param $list
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @param string $root
     * @return array
     */
    public static function listToTree($list, $pk='id', $pid = 'fid', $child = '_child', $root = '0') {
        $tree = array();
        if(is_array($list)) {
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                $parentId =  $data[$pid];
                if ($root == $parentId) {
                    $tree[] = &$list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 多级树
     * @param $list
     * @param int $lv
     * @param string $title
     * @return array
     */
    public static function formatTree($list, $lv = 0, $title = 'name'){
        $formatTree = array();
        foreach($list as $key => $val){
            $title_prefix = '';
            for( $i=0;$i<$lv;$i++ ){
                $title_prefix .= "|---";
            }
            $val['lv'] = $lv;
            $val['namePrefix'] = $lv == 0 ? '' : $title_prefix;
            $val['showName'] = $lv == 0 ? $val[$title] : $title_prefix.$val[$title];
            if(!array_key_exists('_child', $val)){
                array_push($formatTree, $val);
            }else{
                $child = $val['_child'];
                unset($val['_child']);
                array_push($formatTree, $val);
                $middle = self::formatTree($child, $lv+1, $title); //进行下一层递归
                $formatTree = array_merge($formatTree, $middle);
            }
        }
        return $formatTree;
    }
}
