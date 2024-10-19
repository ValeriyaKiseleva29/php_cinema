<?php
namespace App\Http\Controllers;
use App\RMVC\Database\DB;

class Controller
{
    private $filmToken = 'O0NZvxemcwkiq30bsgQoFKEQX6EqiVl7';
    private $filmEndpoint = 'movies';
    private $shortEndpoint = 'short';
    private $apiKey = 'e2c340fa-58e4-4ca8-9726-d8cb6a801707';
    private $cinemaBaseUrl = 'https://kinopoiskapiunofficial.tech/api/v2.2/films';
    private $filmsBaseUrl = 'https://videocdn.tv/api/';
    private $db;

    public function __construct()
    {
        $this->db = new DB();
    }
    public function searchFilmsByTitle($title)
    {
        $url = $this->filmsBaseUrl . "short?api_token=" . $this->filmToken . "&title=" . urlencode($title);
        return $this->makeRequest($url);
    }


    public function getAllFilms()
    {
        $totalPages = 100;

        for ($i = 1; $i <= $totalPages; $i++) {
            $url = $this->filmsBaseUrl . $this->filmEndpoint . "?api_token=" . $this->filmToken . "&page=" . $i;

            $response = $this->makeRequest($url);

            if (isset($response['data']) && !empty($response['data'])) {
                foreach ($response['data'] as $film) {
                    $this->saveFilmToDatabase($film);
                }
            } else {
                break;
            }
        }
    }

    public function saveFilmToDatabase($filmData)
    {
        $sql = "INSERT INTO movies (kp_id, orig_title, title, imdb_id, content_type, iframe_src, year, translations, img_link) 
            VALUES (:kp_id, :orig_title, :title, :imdb_id, :content_type, :iframe_src, :year, :translations, :img_link)";

        $params = [
            'kp_id' => $filmData['kp_id'],
            'orig_title' => $filmData['orig_title'],
            'title' => $filmData['title'],
            'imdb_id' => $filmData['imdb_id'],
            'content_type' => $filmData['content_type'],
            'iframe_src' => $filmData['iframe_src'],
            'year' => $filmData['year'],
            'translations' => json_encode($filmData['translations']),
            'img_link' => $filmData['img_link'] ?? null
        ];

        $this->db->execute($sql, $params);
    }



    public function getImageUrl($id)
    {
        $url = $this->cinemaBaseUrl . "/" . $id;
        $response = $this->makeRequest($url);

        if (isset($response['error'])) {
            return [
                'error' => $response['error'],
                'id' => $id,
                'posterUrl' => "https://images.unsplash.com/photo-1618519764620-7403abdbdfe9?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            ];
        }

        return $response;
    }


    private function makeRequest($url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'X-API-KEY: ' . $this->apiKey,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            curl_close($ch);
            return [
                'error' => curl_error($ch),
            ];
        }

        curl_close($ch);
        return json_decode($response, true);
    }
}
