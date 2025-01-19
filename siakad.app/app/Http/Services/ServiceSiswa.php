<?php

namespace App\Http\Services;

class ServiceSiswa
{
    /**
     * Get siswa by nim
     * @param int $offset offset, default null
     * @param int $limit limit, default null
     * @param string $search search siswa, default null
     * @param string $order order siswa
     * @param string $dir dir siswa, default null asc or desc
     * @param array $where where siswa
     * @return object as data siswa
     */
    public static function all($offset = null, $limit = null, $search = null, $order = null, $dir = null, $where = null)
    {
        $post = [
            'offset' => $offset,
            'limit' => $limit,
            'search' => $search,
            'order' => $order,
            'dir' => $dir,
            'where' => $where != null ? json_encode($where) : null,
        ];

        $apiKey = ServicePmb::APIKEY;
        $url = ServicePmb::URL . "siswa/all";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $apiKey",

        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        return $response;
    }

    public static function find($id)
    {
        $post = [
            'id' => $id,
        ];

        $apiKey = ServicePmb::APIKEY;
        $url = ServicePmb::URL . "siswa/find";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $apiKey",

        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        return $response;
    }

    /**
     * Get siswa by nim
     * @param int $offset offset, default null
     * @param int $limit limit, default null
     * @param string $search search siswa, default null
     * @param string $order order siswa
     * @param string $dir dir siswa, default null asc or desc
     * @param array $where where siswa
     * @return object as data siswa
     */
    public static function count($offset = null, $limit = null, $search = null, $order = null, $dir = null, $where = null)
    {
        $post = [
            'offset' => $offset,
            'limit' => $limit,
            'search' => $search,
            'order' => $order,
            'dir' => $dir,
            'where' => $where != null ? json_encode($where) : null,
        ];

        $apiKey = ServicePmb::APIKEY;
        $url = ServicePmb::URL . "siswa/count";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey: $apiKey",

        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($response);
        return $response;
    }
}
