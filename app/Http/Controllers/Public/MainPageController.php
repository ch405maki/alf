<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MainPageController extends Controller
{
    public function index()
    {
        return inertia('Public/Welcome/Index');
    }
}
