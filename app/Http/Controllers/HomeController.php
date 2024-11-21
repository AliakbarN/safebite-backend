<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View|\Illuminate\Contracts\View\View|Factory|Application
    {
        return view('welcome');
    }

    public function chat(): View|\Illuminate\Contracts\View\View|Factory|Application
    {
        return view('chat');
    }
}
