<?php

namespace Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CryptoCompare
{
    public function __construct()
    {
    }
    /**
     * @param $from
     * @param $to
     * @return mixed
     */
    public static function call_A2B($from = 'usdt', $to = 'usdt')
    {
        $output['A_B']      = $from . '-' . $to;
        $output['rate']     = 0;
        $output['original'] = 0;

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
            $resp = bcmul($resp, 0.98, 3);
            // return $resp;
            $output['rate']     = $resp;
            $output['original'] = $resp;
            return $output;
        } catch (GuzzleException $e) {
            return $output;
            // return $dd->simple_json(0, $th->getMessage());
        }
    }
}
