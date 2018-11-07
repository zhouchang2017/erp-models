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

    public function statusToShipped();

    public function getStatusField(): string;

    public function statusWhenShippedValue(): int;

    public function getShippedAtField(): string;

    public function statusWhenCompletedValue(): int;

    public function getCompletedAtField(): string;

    public function statusToCompleted();

    public function isShipped(): bool;
}