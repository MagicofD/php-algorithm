快速排序的核心思想是：从数列中跳出一个元素，作为“基准”(pivot)
重新排列数组，所有元素比基准小的放在基准前面，
所有元素比基准大的放在基准后面（与基准相同的可以放在任意一边）。
递归地（recursive）把小于基准值元素的子数列和大于基准值元素的子数列排序
C语言中 qsort 函数即为快速排序
---
时间复杂度：O(nlogn)  最差O(n^2)

##### 解题
```
    function quickSort($arr)
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
        $left = $this->quickSort3($left);
        $right = $this->quickSort3($right);
        return array_merge($left, $eq, $right);
    }
```
这种方法创建了三个数组来分别保存大于、等于和小于基准值的元素，时间复杂度为O(nlogn)，空间复杂度为O(3n)即为O(n)。

**那么，能不能不创建新数组来进行排序呢？**
不创建新数组的话，需要在原来的数组上进行操作。
根据快排的思想，需要来把数组分为三个部分，即大于、等于和小于基准值的三部分。
![示意图](https://upload-images.jianshu.io/upload_images/6578832-2011086ac68b10ab.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

如图所示
1. 我们以首个元素为基准值，将元素分为三部分，指针 ```i``` 指向当前进行比较的元素，
区间 [l+1, lt] 是小于基准值的元素，
区间 [lt+1, i-1] 是等于基准值的元素，
从最右边的索引 r 处开始向左，形成的区间 [gt, r] 是大于基准值的元素；
2. 初始状态下，我们需要先确定边界，i 的开始索引指向 l+1，lt的初始值是l，而gt的初始值是r+1，标示三个区域均为空；
3. 排序时：
    * 如果当前 i 指向的元素等于基准值，i + 1；
    * 如果当前 i 指向的元素大于基准值，将 gt-1 处的元素与当前元素交换，然后gt -1，i不变（此时因为换过来的原 gt -1 处的元素还未进行过比较，所以需要在下次循环时再次判断，所以 i 保持不变）；
    * 如果当前 i 指向的元素小于基准值，将lt + 1 处的元素与当前元素交换，然后lt + 1，且i + 1；
    * 最后当 i 走到gt 处，即 ```gt == i``` 时，说明，除了第一个元素（基准值元素），其余的区间已经分配完毕，只要将第一个元素与 lt 处的元素进行交换，然后 lt -1; 这样就形成了三个区间，大于等于和小于基准值。 

###### 代码
交换数组中元素：
```
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
```

数组每个部分的处理（递归的核心）：
```
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
```
其中的一些细节需注意，比如 lt+1、lt-1、gt+1等等这些，如果写错可能就会引起死循环。
这里我们把 lt 赋值为 l，所以初始状态下，lt 对应的元素等于 pivot ，在交换时，需要交换 lt+1 与 i；
gt 初始化时比数组的最大下标多1，所以交换时需要交换 gt-1 与 i；
在循环完成时，lt 及其左边都是小于基准值的元素，gt及其右侧都是大于基准值的元素，此时需要最后一步，交换基准值和 lt ，这一部分的数组处理完成，进入下一次的递归。

快排函数：
``` php
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
```
该算法使用了“分而治之（divide and conquer,D&C）”的思想，可以形成一个递归二叉树，所以时间复杂度为O(nlogn)，空间复杂度为O(1)。
分治算法的解决过程包含两个步骤：
1. 找出基线条件，这种条件尽可能简单；
2. 不断地将问题分解（或者缩小规模），直到符合基线条件