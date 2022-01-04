<?php
/*
 * @Arthur: kk
 * @Date: 2022-01-04 11:41:58
 * @LastEditTime: 2022-01-04 12:14:10
 * @LastEditors: your name
 * @Description: 自動生成 [嚴格紀律 Description]
 * @FilePath: \coin_currency\src\CoinCurrency.php
 * 嚴格紀律
 */

namespace uunmask2000_kk\CoinCurrency;

use Library\CryptoCompare;
use Library\Freecurrencyrates;

class CoinCurrency
{

    public function __construct()
    {
        $this->CryptoCompare = new CryptoCompare();
        $this->Freecurrencyrates = new Freecurrencyrates();
    }
}
