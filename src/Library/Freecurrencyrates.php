<?php

namespace Library;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Freecurrencyrates
{
    /**
     * call_A2B
     *
     *
     * @param  string  $from
     * @param  string  $to
     * @return mixed
     */
    public static function call_A2B($from = 'USDT', $to = 'HKD')
    {
        $output['A_B']      = $from . '-' . $to;
        $output['rate']     = 0;
        $output['original'] = 0;
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
            $array = explode(",", $array[3]);
            // dd($array);
            # Array[0] Data , Array[1] Price
            $output['A_B']      = $from . '-' . $to;
            $output['rate']     = $array[1];
            $output['original'] = $array[1];

            return $output;
        } catch (\Throwable $th) {
            return $output;
        }
    }
}
