<?php

namespace CoinCurrencyService\Library;

use CoinCurrencyService\Common\DAO;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;

class CoinMarketCap
{

    static $fiat_url = 'https://pro-api.coinmarketcap.com/v1/fiat/map';
    static $cryptocurrency_url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/map';

    static $history_url = 'https://api.coinmarketcap.com/data-api/v3/cryptocurrency/detail/chart?id=%s&convertId=%s&range=%s';

    static $conversion_url = 'https://api.coinmarketcap.com/data-api/v3/tools/price-conversion';
    private $cmc_pro_api_key;

    /**
     * 
     * 
     * @param mixed $CoinMarketCapConf
     */
    public function __construct($CoinMarketCapConf)
    {
        // api-key
        $this->cmc_pro_api_key =  $CoinMarketCapConf['cmc_pro_api_key'] ?? '-';
    }


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
            $id = self::getFiatId($code);
            if ($id == 0) {
                $id = self::getCryptocurrencyId($code);
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
                    'symbol' => $symbol,
                ],
            ];
            $resp   = $client->request('get', self::$cryptocurrency_url, $params);
            $resp   = json_decode($resp->getBody(), true);

            $tmp    = $resp['data'];
            // print_r($tmp);
            $arr    = array_column($tmp, 'id', 'symbol');
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
            $resp   = $client->request('get', self::$fiat_url);
            $resp   = json_decode($resp->getBody(), true);

            $tmp    = $resp['data'];
            // print_r($tmp);
            $arr    = array_column($tmp, 'id', 'symbol');
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
        $from               = strtoupper($from);
        $to                 = strtoupper($to);
        $output['A_B']      = $from . '-' . $to;
        $output['rate']     = 0;
        $output['original'] = 0;
        $output['error']    = "";
        try {

            $fromId             = self::getFindId($from);
            $toId               = self::getFindId($to);
            $client             = new Client();
            $params             = [
                'query' => [
                    'amount' => 1,
                    'convert_id' => $toId,
                    'id' => $fromId,
                ],
            ];
            $resp               = $client->request('get', self::$conversion_url, $params);
            $resp               = json_decode($resp->getBody(), true);
            $resp               = $resp['data']['quote'][0]['price'] ?? 0;
            // $resp = bcmul($resp, 1, 3);
            $resp               = DAO::businessBcmul($resp);
            // return $resp;
            $output['rate']     = $resp;
            $output['original'] = $resp;
            return $output;
        } catch (\Throwable $th) {
            // echo $th->getMessage();
            $output['error'] = $th->getMessage();
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
    public static function historyDays($from = 'USD', $to = 'TWD', $day = '1D'): array
    {
        $from                  = strtoupper($from);
        $to                    = strtoupper($to);
        $output['A_B']         = $from . '-' . $to;
        $output['historyDays'] = [];
        $output['error']       = "";
        try {
            $fromId = self::getFindId($from);
            $toId   = self::getFindId($to);
            return self::historyById($fromId, $toId, $day);
            // $client = new Client();
            // $url    = vsprintf(self::$history_url, [$fromId, $toId, $day]);
            // $resp   = $client->request('get', $url);
            // $resp   = json_decode($resp->getBody(), true);
            // // print_r($resp['data']);
            // foreach ($resp['data']['points'] as $key => $value) {
            //     // print_r($value['c'][0]);
            //     $respTmp                 = $value['c'][0];
            //     $tmp                     = [
            //         'time' => $key,
            //         'rate' => DAO::businessBcmul($respTmp),
            //         'original' => DAO::businessBcmul($respTmp),
            //     ];
            //     $output['historyDays'][] = $tmp;
            // }
            // return $output;
        } catch (\Throwable $th) {
            // echo $th->getMessage();
            $output['error'] = $th->getMessage();
            return $output;
        }
    }


    /**
     * @param string $fromId
     * @param string $toId
     * @param string $day
     * 
     * @return array
     */
    public static function historyById($fromId = '1', $toId = '1', $day = '1D'): array
    {
        try {
            $client = new Client();
            $url    = vsprintf(self::$history_url, [$fromId, $toId, $day]);
            $resp   = $client->request('get', $url);
            $resp   = json_decode($resp->getBody(), true);
            foreach ($resp['data']['points'] as $key => $value) {
                // print_r($value['c'][0]);
                $respTmp                 = $value['c'][0];
                $tmp                     = [
                    'time' => $key,
                    'rate' => DAO::businessBcmul($respTmp),
                    'original' => DAO::businessBcmul($respTmp),
                ];
                $output['historyDays'][] = $tmp;
            }
            return $output;
        } catch (\Throwable $th) {
            // echo $th->getMessage();
            $output['error'] = $th->getMessage();
            return $output;
        }
    }

    /**
     * getAllSymbol
     *
     * @return mixed
     */
    public function getAllSymbol($limit = 5000)
    {
        $client          = new Client();
        $promises        = [];
        $output          = [];
        $output['error'] = "";
        try {
            // 限制比數
            $params                     = [
                'query' => [
                    'limit' => (int) ($limit) > 5000 ? 5000 : (int) ($limit),
                    'CMC_PRO_API_KEY' => $this->cmc_pro_api_key 
                ]
            ];
            $promises['flat']           = $client->getAsync(self::$fiat_url, $params);
            $promises['cryptocurrency'] = $client->getAsync(self::$cryptocurrency_url, $params);
            $responses                  = \GuzzleHttp\Promise\Utils::unwrap($promises);
            foreach ($responses as $key => $value) {
                $resp         = json_decode($value->getBody(), true);
                $output[$key] = array_column($resp['data'], null, 'symbol');
            }
        } catch (\Throwable $th) {
            //throw $th;
            $output['flat']           = [];
            $output['cryptocurrency'] = [];
            $output['error']          = $th->getMessage();
        }


        return $output;
    }
}
