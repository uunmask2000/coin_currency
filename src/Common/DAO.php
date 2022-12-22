<?php

namespace CoinCurrencyService\Common;

class DAO
{

    /**
     * businessBcmul
     *
     * 統一匯率 結構
     *
     * @param int $amount
     * @param int $a
     * @param int $b
     *
     * @return mixed
     */
    public static function businessBcmul($amount = 0, $a = 1, $b = 16)
    {
        try {
            $amount = bcmul($amount, $a, $b);
        } catch (\Throwable $th) {
            //throw $th;
            $amount = 0;
        }
        return $amount;
    }
}
