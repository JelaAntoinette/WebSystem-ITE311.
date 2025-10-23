<?php

namespace App\Controllers;

use App\Models\AnnouncementModel;

class Announcement extends BaseController
{
    public function index()
    {
        // Load the model
        $model = new AnnouncementModel();

        // Fetch all announcements
        $data['announcements'] = $model->findAll();

        // Pass data to the view
        return view('announcements', $data);
    }
}
