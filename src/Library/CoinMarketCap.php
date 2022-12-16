<?php

namespace Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class CoinMarketCap
{

    static $fiat_url = 'https://web-api.coinmarketcap.com/v1/fiat/map';
    static $cryptocurrency_url = 'https://web-api.coinmarketcap.com/v1/cryptocurrency/map';

    static $history_url = 'https://api.coinmarketcap.com/data-api/v3/cryptocurrency/detail/chart?id=%s&convertId=%s&range=%s';

    static $conversion_url = 'https://api.coinmarketcap.com/data-api/v3/tools/price-conversion';


    /**
     * getFindId
     *
     * @param mixed $code
     *
     * @return mixed
     */
    private static function getFindId($code)
    {
        $id = 0;
        try {
            $id = self::getCryptocurrencyId($code);
            if ($id == 0) {
                $id = self::getFiatId($code);
            }
            return $id;
        } catch (\Throwable $th) {
            throw $th;
        }
    }


    /**
     * getCryptocurrencyId
     *
     * @param mixed $symbol
     *
     * @return mixed
     */
    private static function getCryptocurrencyId($symbol)
    {
        $output = 0;
        try {

            $client = new Client();
            $params = [
                'query' => [
                    'symbol'  => $symbol,
                ],
            ];
            $resp = $client->request('get', self::$cryptocurrency_url, $params);
            $resp = json_decode($resp->getBody(), true);

            $tmp = $resp['data'];
            // print_r($tmp);
            $arr = array_column($tmp, 'id', 'symbol');
            // print_r($arr);
            $output = $arr[$symbol] ?? 0;

            return $output;
        } catch (\Throwable $th) {
            return $output;
        }
    }


    /**
     * getFiatId
     *
     * @param mixed $symbol
     *
     * @return mixed
     */
    private static function getFiatId($symbol)
    {
        $output = 0;
        try {

            $client = new Client();
            $resp = $client->request('get', self::$fiat_url);
            $resp = json_decode($resp->getBody(), true);

            $tmp = $resp['data'];
            // print_r($tmp);
            $arr = array_column($tmp, 'id', 'symbol');
            // print_r($arr);
            $output = $arr[$symbol] ?? 0;

            return $output;
        } catch (\Throwable $th) {
            return $output;
        }
    }


    /**
     * call_A2B
     *
     * @param string $from
     * @param string $to
     *
     * @return mixed
     */
    public static function call_A2B($from = 'USD', $to = 'TWD'): array
    {
        $from   = strtoupper($from);
        $to     = strtoupper($to);
        $output['A_B'] = $from . '-' . $to;
        $output['rate'] = 0;
        $output['original'] = 0;

        try {

            $fromId = self::getFindId($from);
            $toId =   self::getFindId($to);
            $client = new Client();




            $params = [
                'query' => [
                    'amount'  => 1,
                    'convert_id'  => $toId,
                    'id'  =>   $fromId,
                ],
            ];
            $resp = $client->request('get', self::$conversion_url, $params);
            $resp = json_decode($resp->getBody(), true);
            $resp = $resp['data']['quote'][0]['price'] ?? 0;
            $resp = bcmul($resp, 1, 3);
            // return $resp;
            $output['rate']     = $resp;
            $output['original'] = $resp;
            return $output;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return $output;
        }
    }


    /**
     * historyDays
     *
     * @param string $from
     * @param string $to
     *
     * @return array
     */
    public static function historyDays($from = 'USD', $to = 'TWD'): array
    {
        $from   = strtoupper($from);
        $to     = strtoupper($to);
        $output['A_B']      = $from . '-' . $to;
        $output['historyDays'] = [];
        try {
            $fromId = self::getFindId($from);
            $toId =   self::getFindId($to);
            $client = new Client();
            $url = vsprintf(self::$history_url, [$fromId, $toId, '1D']);
            $resp = $client->request('get', $url);
            $resp = json_decode($resp->getBody(), true);
            // print_r($resp['data']);
            foreach ($resp['data']['points'] as $key => $value) {
                // print_r($value['c'][0]);
                $tmp = [
                    'time' => $key,
                    'rate' => bcmul($value['c'][0] ?? 0, 1, 3),
                    'original' => bcmul($value['c'][0] ?? 0, 1, 3),
                ];
                $output['historyDays'][] = $tmp;
            }
            return $output;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return $output;
        }
    }
}
