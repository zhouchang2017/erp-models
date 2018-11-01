<?php
/**
 * Created by PhpStorm.
 * User: zhouchang
 * Date: 2018/11/1
 * Time: 下午11:39
 */

namespace Chang\Erp\Contracts;


interface Orderable
{
    public function getStatus(): string;

    public function register();

    public function getCreatedAt();

    public function getUpdatedAt();

    public function getMarketIdAttribute();

    public function items();

    public function order();

    static public function rules(): array;

    static public function messages(): array;
}