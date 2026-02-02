<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Project;
use App\Experience;
use App\Video;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index', [
            'projects' => Project::count(),
            'experiences' => Experience::count(),
            'videos' => Video::count(),
        ]);
    }
}
