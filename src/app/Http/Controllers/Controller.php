<?php
namespace App\Http\Controllers;

class Controller
{
    private $filmToken = 'O0NZvxemcwkiq30bsgQoFKEQX6EqiVl7';
    private $filmEndpoint = 'movies';
    private $shortEndpoint = 'short';
    private $apiKey = 'e2c340fa-58e4-4ca8-9726-d8cb6a801707';
    private $cinemaBaseUrl = 'https://kinopoiskapiunofficial.tech/api/v2.2/films';
    private $filmsBaseUrl = 'https://videocdn.tv/api/';

    // Функция для поиска фильма по названию
    public function searchFilmsByTitle($title)
    {
        $url = $this->filmsBaseUrl . "short?api_token=" . $this->filmToken . "&title=" . urlencode($title);
        return $this->makeRequest($url);
    }

    // Получение всех фильмов с возможностью постраничного просмотра
    public function getAllFilms($page = null)
    {
        $url = $this->filmsBaseUrl . $this->filmEndpoint . "?api_token=" . $this->filmToken;
        if ($page) {
            $url .= "&page=" . $page;
        }
        return $this->makeRequest($url);
    }

    // Получение одного фильма по его ID
    public function getOneFilm($id)
    {
        $url = $this->filmsBaseUrl . $this->shortEndpoint . "?api_token=" . $this->filmToken . "&kinopoisk_id=" . $id;
        return $this->makeRequest($url);
    }

    // Получение информации о фильме и его постер
    public function getImageUrl($id)
    {
        $url = $this->cinemaBaseUrl . "/" . $id;
        $response = $this->makeRequest($url);

        // Если произошла ошибка, возвращаем фейковый постер
        if (isset($response['error'])) {
            return [
                'error' => $response['error'],
                'id' => $id,
                'posterUrl' => "https://images.unsplash.com/photo-1618519764620-7403abdbdfe9?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D",
            ];
        }

        return $response;
    }

    // Функция для выполнения запросов через cURL
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
