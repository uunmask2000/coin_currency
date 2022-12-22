<?php

# use CoinCurrency
namespace CoinCurrencyService;

use CoinCurrencyService\Library\CoinMarketCap;
use CoinCurrencyService\Library\CryptoCompare;
use CoinCurrencyService\Library\Freecurrencyrates;

class CoinCurrency
{

    public $CryptoCompare;
    public $Freecurrencyrates;
    public $CoinMarketCap;


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
