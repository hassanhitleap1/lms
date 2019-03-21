<?php
/**
 * Created by PhpStorm.
 * User: Saleh
 * Date: 9/17/2018
 * Time: 5:13 PM
 */

namespace App\Helper;


class ApiHelper
{

    public static function getmediaAPI($mediatype, $category, $grade, $search, $page)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => config('lms.URL_API') . "media?type=" . $mediatype . "&category=" . $category . "&grade=" . $grade . "&keyword=" . $search,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            $response = [];
        }
        $media = json_decode($response, true);
        $collection = collect($media);
        $data = new \Illuminate\Pagination\LengthAwarePaginator(
            $collection->forPage($page, config('lms.pagination')),
            $collection->count(),
            config('lms.pagination'),
            $page
        );

        return $data;

    }
}