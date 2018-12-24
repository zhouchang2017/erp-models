<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/1
 * Time: 下午11:39
 */

namespace Chang\Erp\Contracts;


use Chang\Erp\Models\ExpendItems;

interface Orderable
{
    // 获取订单状态
    public function getStatus(): string;

    // 注册订单参数
    public function register();

    // 订单创建时间
    public function getCreatedAt();

    // 订单更新时间
    public function getUpdatedAt();

    // 订单归属商店id
    public function getMarketId();

    // 子订单
    public function items();

    // 总order关联材料
    public function order();

    // 同步至订单库
    public static function syncOrder($orderId);

    // 同步至订单库
    public function sync();

    // 检测所有订单同步
    public static function syncAll();

    // 获取订单总价格
    public function getTotalPrice(): string;

    // 获取产生出货单参数列表
    public function getExpendItemList(): ExpendItems;

    // 订单简单地址
    public function getSimpleAddressAttribute(): string;

}