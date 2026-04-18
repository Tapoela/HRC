<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\FixtureModel;
use App\Controllers\Admin\BaseAdmin;

class Fixtures extends BaseAdmin
{
    use DecodesHashId;

    public function index()
    {
        $fixtures = (new FixtureModel())
            ->orderBy('match_date', 'ASC')
            ->findAll();
        $auth = service('authorization', false);
        if ($auth && method_exists($auth, 'inGroup')) {
            $canEdit = $auth->inGroup('coach');
        } else {
            $canEdit = (session('role_name') === 'coach' || session('role_id') == 2);
        }
        return view('admin/fixtures/index', [
            'fixtures' => $fixtures,
            'canEdit' => $canEdit
        ]);
    }

    public function create()
    {
        return view('admin/fixtures/create');
    }

    public function store()
    {
        (new FixtureModel())->insert([
            'team'        => $this->request->getPost('team'),
            'opponent'    => $this->request->getPost('opponent'),
            'match_date'  => $this->request->getPost('match_date'),
            'match_time'  => $this->request->getPost('match_time'),
            'venue'       => $this->request->getPost('venue'),
            'venue_name'  => $this->request->getPost('venue_name'),
        ]);

        return redirect()->to('/admin/fixtures')
            ->with('success', 'Fixture added');
    }

    public function update()
    {
        $id = $this->request->getPost('id');

        (new FixtureModel())->update($id, [
            'team'        => $this->request->getPost('team'),
            'opponent'    => $this->request->getPost('opponent'),
            'match_date'  => $this->request->getPost('match_date'),
            'match_time'  => $this->request->getPost('match_time'),
            'venue'       => $this->request->getPost('venue'),
            'venue_name'  => $this->request->getPost('venue_name'),
        ]);

        return redirect()->to('/admin/fixtures')
            ->with('success', 'Fixture updated');
    }

    public function delete($hash)
    {
        $id = $this->decodeHash($hash);

        (new FixtureModel())->delete($id);

        return redirect()->to('/admin/fixtures')
            ->with('success', 'Fixture deleted');
    }
}
