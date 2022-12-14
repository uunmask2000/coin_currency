# coin_currency

[TWD-USD](https://api.jim-kk.com/api/currency/converter?from=TWD&to=USD)
```text
require 'vendor/autoload.php';

use \uunmask2000_kk\CoinCurrency\CoinCurrency;

$class = new CoinCurrency();
print_r($class->CryptoCompare->call_A2B());
print_r($class->Freecurrencyrates->call_A2B());
EX :
print_r($CoinCurrency->CryptoCompare->call_A2B('usdt', 'hkd'));
print_r($CoinCurrency->Freecurrencyrates->call_A2B('usdt', 'hkd'));

Array
(
    [A_B] => usdt-hkd
    [rate] => 7.627
    [original] => 7.627
)
Array
(
    [rate] => 7.7969157151701
    [original] => 7.7969157151701
    [A_B] => usdt-hkd
)
```
