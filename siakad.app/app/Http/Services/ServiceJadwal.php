<?php
namespace App\Http\Services;

class ServiceJadwal
{
    public static function bentrokDosen($offset = null, $limit = null, $dosenId = null, $thAkademikId = null, $hariId = null, $jam = null)
    {
        $post = [
            'offset' => $offset,
            'limit' => $limit,
            'dosen_id' => $dosenId,
            'th_akademik_id' => $thAkademikId,
            'hari_id' => $hariId,
            'jam' => $jam,
        ];

        $apiKey = config('simkeu.simkeu_api_key');
        $url = config('simkeu.simkeu_url') . "jadwal/dosenBentrok";
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