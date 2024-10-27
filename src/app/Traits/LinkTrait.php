<?php

namespace App\Traits;

trait LinkTrait
{
    public function buildLink($id, $api)
    {
        return  "http://www.omdbapi.com/?i={$id}&apikey={$api}";
    }
    }