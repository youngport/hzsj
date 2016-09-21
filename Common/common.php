<?php
/**
 * 获取周期类型描述
 */
function descRateTimetype($ptype) {
    $desc = S('RateTime');
    return $desc[$ptype];
}
/**
 * 获取卡类型描述
 */
function descCardtype($ptype) {
    $desc = S('CardTYPE');
    return $desc[$ptype];
}
/**
 * 获取发卡组织描述
 */
function descCardCroptype($ptype) {
    $desc = S('CardCropTYPE');
    return $desc[$ptype];
}