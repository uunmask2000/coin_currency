<?php

namespace CoinCurrencyService\Common;

class DAO
{


    /**
     * businessBcmul
     *
     * @param int $amount
     * @param int $a
     * @param int $b
     *
     * @return mixed
     */
    public static function businessBcmul($amount = 0, $a = 1, $b = 16) : float
    {
        try {
            $amount = bcmul($amount, $a, $b);
        } catch (\Throwable $th) {
            //throw $th;
            $amount = 0;
        }
        return doubleval($amount);
    }
}
