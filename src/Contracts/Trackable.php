<?php
/**
 * Created by PhpStorm.
 * User: z
 * Date: 2018/11/2
 * Time: 3:55 PM
 */

namespace Chang\Erp\Contracts;


interface Trackable
{
    public function tracks();

    // 标记已发货
    public function statusToShipped();

    // 获取状态字段
    public function getStatusField(): string;

    // 状态为已发货的值
    public function statusWhenShippedValue(): int;

    // 获取发货时间字段
    public function getShippedAtField(): string;

    // 状态为确认收货的值
    public function statusWhenCompletedValue(): int;

    // 获取确认收货时间字段
    public function getCompletedAtField(): string;

    // 标记状态确认收货|已完成
    public function statusToCompleted();

    // 是否发货
    public function isShipped(): bool;

    // 发货前的钩子
    public function beforeShipped();

    // 发货后钩子
    public function afterShipped();

    // 完成前的钩子
    public function beforeCompleted();

    // 完成后钩子
    public function afterCompleted();

}