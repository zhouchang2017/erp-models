<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 4:03 PM
 */

namespace Chang\Erp\Contracts;


use Chang\Erp\Models\ExpendItems;

interface Expendable
{
    // 关联出库
    public function inventoryExpends();

    // 关联出库
    public function inventoryExpend();

    // 获取即将出货的列表
    public function getExpendItemList(): ExpendItems;

    // 创建出货单描述
    public function getDescription(): string;

    // 重置出货单
    public function reExpend();

    // 取消出货
    public function cancelExpend();
}