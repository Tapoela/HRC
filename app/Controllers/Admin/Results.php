<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ResultModel;

use App\Controllers\Admin\BaseAdmin;

class Results extends BaseAdmin
{
    use DecodesHashId;
    public function index()
    {
        $results = (new ResultModel())
            ->orderBy('match_date', 'DESC')
            ->findAll();

        return view('admin/results/index', [
            'results' => $results
        ]);
    }

    public function create()
    {
        return view('admin/results/create');
    }

    public function store()
    {
        (new ResultModel())->insert([
            'team'           => $this->request->getPost('team'),
            'opponent'       => $this->request->getPost('opponent'),
            'team_score'     => $this->request->getPost('team_score'),
            'opponent_score' => $this->request->getPost('opponent_score'),
            'match_date'     => $this->request->getPost('match_date'),
        ]);

        return redirect()->to('/admin/results')
            ->with('success', 'Result added successfully');
    }

    public function delete($hash)
    {
        $id = $this->decodeHash($hash);
        (new ResultModel())->delete($id);

        return redirect()->to('/admin/results')
            ->with('success', 'Result deleted');
    }
}
