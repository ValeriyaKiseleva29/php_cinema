<?php

namespace App\Http\Controllers;

use App\RMVC\View\View;

abstract class BaseController extends Controller
{

    protected function render(string $viewPath, array $data = []): string
    {
        return View::view($viewPath, $data);
    }

    abstract public function index();
}
