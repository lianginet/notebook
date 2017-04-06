<?php

/**
 * 当时间为2017-03-31时，得到结果201705
 */
echo date('Ym', strtotime("+1 month")); // 当前时间为2017-03-31时错误

/**
 * 如 strtotime("201703 +1 month")
 * 201703不会识别为时间格式
 * 当时间非本月时间时，比如当月4月，使用strtotime("201703 +1 month") 得到的仍然是201705
 * 当时间为2017-03-31时，得到结果201705
 */
echo date('Ym', strtotime(date('Ym')."+1 month"));


######
echo date('Ym', strtotime(date('Y-m')."+1 month")); // 正确