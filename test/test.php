<?php

require('vendor/autoload.php');


use CoinCurrencyService\CoinCurrency;

$coinCurrency = new CoinCurrency();
// var_dump($coinCurrency);
foreach ($coinCurrency as $key => $value) {
    // $tmp = $key->call_A2B();
    print_r($key);
    print_r($coinCurrency->$key->call_A2B());
    // print_r($coinCurrency->$key->historyDays());
}
// print_r($coinCurrency->Freecurrencyrates->call_A2B());
// print_r($coinCurrency->CryptoCompare->historyDays());
// print_r($coinCurrency->Freecurrencyrates->historyDays());

// print_r($coinCurrency->CoinMarketCap->call_A2B('SGD', 'TWD'));


// print_r($coinCurrency->CoinMarketCap->getAllSymbol(2));
