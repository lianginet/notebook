<?php

## 概率
## 概率计算
## 待完善

function raffle($raffleArr)
{
    $total = 0;
    $newRafs = [];

    $maxDigit = 0;
    foreach ($raffleArr as $raf) {
        $total += $raf['prob'];
        $newRafs[$raf['id']] = $raf['prob'];

        // 最大小数点位数
        $digit =mb_strlen(($raf['prob']-(int)$raf['prob'])) - 2;
        $maxDigit = $maxDigit > $digit ? $maxDigit : $digit;
    }

    asort($newRafs);

    // 倍数
    $multiple = 1;
    while($maxDigit > 0) {
        $multiple *= 10;
        -- $maxDigit;
    }

    $start = 0;
    foreach ($newRafs as $k => $raf) {
        $start = $newRafs[$k] = $raf * $multiple + $start;
    }

    $num = mt_rand() % ($total * $multiple);

    foreach ($newRafs as $key => $raf) {
        if ($num < $raf) {
            return $key;
        }
    }

    return false;
}

$raffleArr = [
    ['id' => 6, 'name' => '未中奖', 'prob' => 0.684],
    ['id' => 1, 'name' => '特等奖', 'prob' => 0.001],
    ['id' => 2, 'name' => '一等奖', 'prob' => 0.005],
    ['id' => 3, 'name' => '二等奖', 'prob' => 0.01],
    ['id' => 4, 'name' => '三等奖', 'prob' => 0.1],
    ['id' => 5, 'name' => '四等奖', 'prob' => 0.2],
];
 
$id = raffle($raffleArr);

$arr = [
    1 => 0,
    2 => 0,
    3 => 0,
    4 => 0,
    5 => 0,
    6 => 0,
];
// 10w次测试
for ($i = 0; $i < 100000; $i ++) {
    $id = raffle($raffleArr);
    ++$arr[$id];
}

var_dump($arr);
## 结果，接近比例
// array (size=6)
//   1 => int 95
//   2 => int 547
//   3 => int 1012
//   4 => int 10082
//   5 => int 19829
//   6 => int 68435