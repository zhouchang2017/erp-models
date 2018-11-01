<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/1
 * Time: 下午11:10
 */

namespace Chang\Erp\Contracts;

interface Marketable
{
    public function getName(): string;

    public function register();
}