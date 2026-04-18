<?php

namespace App\Controllers;

use App\Models\ResultModel;
use App\Models\FixtureModel;

class PublicSite extends BaseController
{
    public function home()
    {
        return view('public/home', [
            'title' => 'Heidelberg Rugby Club'
        ]);
    }

    public function about()
    {
        return view('public/about', [
            'title' => 'About | Heidelberg Rugby Club'
        ]);
    }

    public function teams()
    {
        return view('public/teams', [
            'title' => 'Teams | Heidelberg Rugby Club'
        ]);
    }

    public function fixtures()
    {
        $today = date('Y-m-d');

        $fixtures = (new FixtureModel())
            ->where('match_date >=', $today)   // 👈 hide past games
            ->orderBy('match_date', 'ASC')
            ->orderBy('match_time', 'ASC')
            ->findAll();

        return view('public/fixtures', [
            'fixtures' => $fixtures
        ]);
    }

    public function results()
    {
        $results = (new ResultModel())
            ->orderBy('match_date', 'DESC')
            ->findAll();

        return view('public/results', [
            'title'   => 'Results | Heidelberg Rugby Club',
            'results' => $results
        ]);
    }

    public function contact()
    {
        return view('public/contact', [
            'title' => 'Contact | Heidelberg Rugby Club'
        ]);
    }

    public function apiEvents()
    {
        $model = new \App\Models\EventModel();
        $events = $model->orderBy('start','asc')->findAll();
        return $this->response->setJSON($events);
    }

    public function events()
    {
        return view('public/events', [
            'title' => 'Events | Heidelberg Rugby Club'
        ]);
    }
}
