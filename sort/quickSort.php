<?php

$arr = [50,12,98,56,34,78,23,1,2,5,4,7,6,78,54,24,54,321,564,17, 28, 35,36,87,95,57,87,96,105,689,45,78,82,51,24,54,321,564,19, 28, 35,36,87,987,57,87,96,105,689,45,78,82,52,24,54,321,514,11, 28, 35,36,87,96,57,87,96,105,689,45,78,82,56,51,12,92,56,34,78,23,1,2,5,4,7,6,78,54,24,54,321,534,10, 28, 57, 77, 89, 95, 96, 58, 32, 24, 27, 17, 19, 13, 63, 67,8,82,53,24,54,321,564,12, 28, 35,36,87,93,57,87,96,105,689,45,78,82,55,59,18,91,57,34,78,23,124,5775,514,4,75,6,78,54,24,54,321,544,21, 28, 57, 77, 89, 95, 96, 58, 32, 24, 27, 17, 19, 13, 63, 67];
$arr1 = [50,12,98,56,34,78,23,1,2,5,4,7,6,78,54,24,54,321,564,12,54,657,321,354,86,1,54,145,48,50,12,98,56,34,78,23,1];
$arr3 = [50,12,98,56,34,12, 50, 60, 68, 50];
$arr4 = [5,8,3,6,4];

$arr3 = quickSort3($arr3);
var_dump($arr3);die;

function quickSort(array &$arr, $l=0, $r=null)
{
    $count = count($arr);
    if ($count > 1) {
        if ($r === null) {
            $r = $count - 1;
        }

        if ($l < $r) {
            $ltgt = partion($arr, $l, $r);
            //var_dump($ltgt, $arr, $l, $r);die;
            // 小于的部分
            quickSort($arr, $l, $ltgt['lt']);
            // 大于的部分
            quickSort($arr, $ltgt['gt'], $r);
            //var_dump($ltgt, $arr, $l, $r);die;
        }
    }
}

/**
 * 数组的处理
 * @param array $arr
 * @param $l
 * @param $r
 * @return array
 */
function partion(array &$arr, $l, $r)
{
    // 基准值
    $pivot = $arr[$l];
    $lt = $l;
    $gt = $r + 1;
    // 这里需要注意细节 lt和gt的初始化和下边的 交换要谨慎

    for ($i = $l + 1; $i < $gt; ) {  // i 要从 l+1 开始
        if ($arr[$i] < $pivot) {
            // 交换lt+1 与 i  lt++
            swap($arr, $lt + 1, $i);    // 此时 lt == pivot, 所以交换lt后边的元素
            $lt ++;
            $i ++;
        } elseif ($arr[$i] > $pivot) {
            // 交换gt-1 与 i
            swap($arr, $gt-1, $i);    // 此时gt是不存在的（原始数组情况下），所以交换 gt-1
            $gt --;
        } else {
            $i ++;
        }
    }
    // 最后交换初始元素和lt( [l+1, lt] 都是小于 基准值的)
    if ($lt > 0) {
        swap($arr, $l, $lt);
        $lt --;  // lt左移一位
    }

    return ['lt' => $lt, 'gt' => $gt];
}



/**
 * 交换数组中两个元素
 * @param $arr
 * @param $a
 * @param $b
 */
function swap(&$arr, $a, $b)
{
    $temp = $arr[$a];
    $arr[$a] = $arr[$b];
    $arr[$b] = $temp;
}






// 还有一种空间复杂度为O(n)的解法，更容易理解
function quickSort3(array $arr)
{
    $count = count($arr);
    if ($count <= 1) {
        return $arr;   // 原样返回数组
    }

    // 定义基准值 pivot
    $pivot = $arr[0];  // 可以任意取一个元素作为基准值，这里选择第一个元素
    $left = $right = [];   // 初始化两个数组，作为存放小于基准值和大于基准值的元素
    $eq = [];   // 相等的部分

    for ($i = 0; $i < $count; $i ++) {
        if ($arr[$i] < $pivot) {
            // 放入左数组
            $left[] = $arr[$i];
        } elseif ($arr[$i] > $pivot) {
            $right[] = $arr[$i];
        } else {
            $eq[] = $arr[$i];
        }
    }

    // 递归
    $left = quickSort3($left);
    $right = quickSort3($right);
    return array_merge($left, $eq, $right);
}
