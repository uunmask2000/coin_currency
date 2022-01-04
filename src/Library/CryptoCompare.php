<?php
/*
 * @Arthur: kk
 * @Date: 2022-01-04 11:48:49
 * @LastEditTime: 2022-01-04 11:52:00
 * @LastEditors: your name
 * @Description: 自動生成 [嚴格紀律 Description]
 * @FilePath: \coin_currency\src\Library\CryptoCompare.php
 * 嚴格紀律
 */

namespace Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CryptoCompare
{
    public function __construct()
    {
    }

    public static function call_A2B($from = 'usdt', $to = 'usdt')
    {
        $output['A_B']     = $from . '-' . $to;
        $output['rate']     = 0;
        $output['original'] = 0;

        try {
            $cache_key = 'check_' . $from . $to . date("Ymd");
            //使用GuzzleHTTP发送get请求
            $url    = 'https://min-api.cryptocompare.com/data/price';
            $client = new Client();
            $from   = strtoupper($from);
            $to     = strtoupper($to);
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
            $output['rate']     =            $resp;
            $output['original'] =            $resp;
            return $output;
        } catch (GuzzleException $e) {
            return $output;
            // return $dd->simple_json(0, $th->getMessage());
        }
    }
}
