<?php

namespace CoinCurrencyService\Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CryptoCompare
{


    /**
     * call_A2B
     *
     * @param string $from
     * @param string $to
     *
     * @return mixed
     */
    public static function call_A2B($from = 'USDT', $to = 'TWD') : array
    {
        $output['A_B']      = $from . '-' . $to;
        $output['rate']     = 0;
        $output['original'] = 0;
        $output['error'] = "";
        try {
            $from   = strtoupper($from);
            $to     = strtoupper($to);
            if ($from == $to) {
                $output['rate']     = 1;
                $output['original'] = 1;
                return $output;
            }
            //使用GuzzleHTTP发送get请求
            $url    = 'https://min-api.cryptocompare.com/data/price';
            $client = new Client();

            $params = [
                'query' => [
                    'fsym'  => $from,
                    'tsyms' => $to,
                ],
            ];
            $resp = $client->request('get', $url, $params);
            // $resp = json_decode($resp->getBody(), true)[$to];
            $resp = json_decode($resp->getBody(), true);
            $resp = $resp[$to];

            # 平均 0.88
            $resp = bcmul($resp, 0.55, 3);
            // return $resp;
            $output['rate']     = $resp;
            $output['original'] = $resp;
            return $output;
        } catch (\Throwable $e) {
            $output['error'] = $e->getMessage();
            return $output;
            // return $dd->simple_json(0, $th->getMessage());
        }
    }


    /**
     * historyDays
     *
     * @param string $from
     * @param string $to
     *
     * @return mixed
     */
    public static function historyDays($from = 'USDT', $to = 'TWD') : array
    {
        $output['A_B']      = $from . '-' . $to;
        $output['historyDays'] = [];
        $output['error'] = "";
        try {
            $from   = strtoupper($from);
            $to     = strtoupper($to);


            $url    = 'https://min-api.cryptocompare.com/data/v2/histoday';
            $client = new Client();

            $params = [
                'query' => [
                    'fsym'  => $from,
                    'tsym' => $to,
                ],
            ];
            $resp = $client->request('get', $url, $params);
            // $resp = json_decode($resp->getBody(), true)[$to];
            $resp = json_decode($resp->getBody(), true);
            if (!empty($resp['Data']['Data'])) {
                foreach ($resp['Data']['Data'] as $key => $value) {
                    $output['historyDays'][] = [
                        'time' => $value['time'],
                        'rate' => bcmul($value['close'], 0.55, 3),
                        'original' => bcmul($value['close'], 0.55, 3),
                    ];
                }
            }


            return $output;
        } catch (\Throwable $th) {
            $output['error'] = $th->getMessage();
            return $output;
        }
    }
}
