<?php

namespace Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Freecurrencyrates
{

    /**
     * getData
     *
     * @param string $from
     * @param string $to
     *
     * @return mixed
     */
    private static function getData($from = 'USDT', $to = 'TWD'): array
    {
        try {
            #https: //freecurrencyrates.com/api/plot_v1.php?b=USDT&t=CNY&s=fcr

            //使用GuzzleHTTP发送get请求
            $url   = 'https://freecurrencyrates.com/api/plot_v1.php?';
            $param = [
                'b' => $from,
                't' => $to,
                's' => 'fcr',
            ];
            $client   = new Client();
            $response = $client->request('GET', $url, ['query' => $param]);

            $body   = $response->getBody()->getContents();
            $status = $response->getStatusCode();
            # String -> Array
            $array = explode("\n", $body);
            // dd($body);
            # Array[3] -> New Array

            unset($array[0]);
            unset($array[1]);
            $array = array_values($array);
            $tmp = [];
            if (!empty($array)) {
                foreach ($array as $key => $value) {
                    if ($value != "") {
                        $tmp[] = explode(",", $value);
                    }
                }
            }
            return $tmp;
        } catch (\Throwable $th) {
            echo $th->getMessage();
            return [];
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
    public static function call_A2B($from = 'USDT', $to = 'TWD'): array
    {
        $output['A_B']      = $from . '-' . $to;
        $output['rate']     = 0;
        $output['original'] = 0;
        try {
            $array = self::getData($from, $to);
            if (!empty($array)) {
                $output['rate']     = bcmul($array[0][1], 1, 3);
                $output['original'] = bcmul($array[0][1], 1, 3);
            }

            return $output;
        } catch (\Throwable $th) {
            return $output;
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
    public static function historyDays($from = 'USDT', $to = 'TWD'): array
    {
        $output['A_B']      = $from . '-' . $to;
        $output['historyDays'] = [];
        try {
            $array = self::getData($from, $to);
            if (!empty($array)) {
                foreach ($array as $key => $value) {
                    $output['historyDays'][] = [
                        'time' => strtotime($value[0]),
                        'rate' => bcmul($value[1], 1, 3),
                        'original' => bcmul($value[1], 1, 3),
                    ];
                }
            }

            return $output;
        } catch (\Throwable $th) {
            return $output;
        }
    }
}
