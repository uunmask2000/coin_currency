<?php

# use CoinCurrency
namespace uunmask2000_kk\CoinCurrency;

use Library\CoinMarketCap;
use Library\CryptoCompare;
use Library\Freecurrencyrates;

class CoinCurrency
{
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->CryptoCompare = new CryptoCompare();
        $this->Freecurrencyrates = new Freecurrencyrates();
        $this->CoinMarketCap = new CoinMarketCap();
    }
}
