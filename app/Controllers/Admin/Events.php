<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\EventModel;
use App\Models\FixtureModel;

class Events extends BaseController
{
    public function index()
    {
        return view('admin/events/index');
    }

    public function calendar()
    {
        return view('admin/events/calendar');
    }

    public function list()
    {
        $model = new EventModel();
        $events = $model->orderBy('start','asc')->findAll();
        return $this->response->setJSON($events);
    }

    public function create()
    {
        $data = $this->request->getPost();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|max_length[200]',
            'start' => 'required|valid_date',
            'end'   => 'permit_empty|valid_date',
        ]);
        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ])->setStatusCode(422);
        }
        $model = new EventModel();
        if (!$model->insert($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $model->errors() ?: ['db' => 'Failed to insert event.']
            ])->setStatusCode(500);
        }
        // If Match Day, create fixture
        if (($data['type'] ?? '') === 'Match Day') {
            $fixtureData = [
                'team' => $data['team'] ?? '',
                'opponent' => $data['opponent'] ?? '',
                'match_date' => $data['match_date'] ?? '',
                'match_time' => $data['match_time'] ?? '',
                'venue' => $data['venue'] ?? '',
                'venue_name' => $data['venue_name'] ?? '',
            ];
            $fixtureModel = new FixtureModel();
            $fixtureModel->insert($fixtureData);
        }
        return $this->response->setJSON(['status'=>'success','id'=>$model->getInsertID()]);
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $validation = \Config\Services::validation();
        $validation->setRules([
            'title' => 'required|max_length[200]',
            'start' => 'required|valid_date',
            'end'   => 'permit_empty|valid_date',
        ]);
        if (!$validation->run($data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $validation->getErrors()
            ])->setStatusCode(422);
        }
        $model = new EventModel();
        if (!$model->update($id, $data)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $model->errors() ?: ['db' => 'Failed to update event.']
            ])->setStatusCode(500);
        }
        // If Match Day, update or create fixture
        if (($data['type'] ?? '') === 'Match Day') {
            $fixtureData = [
                'team' => $data['team'] ?? '',
                'opponent' => $data['opponent'] ?? '',
                'match_date' => $data['match_date'] ?? '',
                'match_time' => $data['match_time'] ?? '',
                'venue' => $data['venue'] ?? '',
                'venue_name' => $data['venue_name'] ?? '',
            ];
            $fixtureModel = new FixtureModel();
            // Try to find a fixture for this match date/time/team/opponent
            $fixture = $fixtureModel->where($fixtureData)->first();
            if ($fixture) {
                $fixtureModel->update($fixture['id'], $fixtureData);
            } else {
                $fixtureModel->insert($fixtureData);
            }
        }
        return $this->response->setJSON(['status'=>'success']);
    }

    public function delete($id)
    {
        $model = new EventModel();
        if (!$model->find($id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => ['not_found' => 'Event not found.']
            ])->setStatusCode(404);
        }
        if (!$model->delete($id)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $model->errors() ?: ['db' => 'Failed to delete event.']
            ])->setStatusCode(500);
        }
        return $this->response->setJSON(['status'=>'success']);
    }
}
