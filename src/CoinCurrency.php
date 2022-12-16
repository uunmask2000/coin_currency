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
        // A
        $this->CryptoCompare = new CryptoCompare();
        // B
        $this->Freecurrencyrates = new Freecurrencyrates();
        // C
        $this->CoinMarketCap = new CoinMarketCap();
    }
}
