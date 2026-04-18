<?php
namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TeamSelectionModel;
use App\Models\FixtureModel;
use App\Models\UserModel;

class TeamSelection extends BaseController
{
    use DecodesHashId; // Use the DecodesHashId trait

    protected $teamModel;
    protected $fixtureModel;

    public function __construct()
    {
        $this->teamModel = new TeamSelectionModel();
        $this->fixtureModel = new FixtureModel();
    }

    // List all team selections
    public function index()
    {
        $teams = $this->teamModel->orderBy('created_at', 'DESC')->findAll();
        // Attach fixture and coach names for display
        foreach ($teams as &$team) {
            $fixture = $this->fixtureModel->find($team['fixture_id']);
            $team['fixture_name'] = $fixture ? ($fixture['team'] . ' vs ' . $fixture['opponent'] . ' @ ' . ($fixture['venue_name'] ?? $fixture['venue'] ?? '')) : 'Unknown';
            $team['coach_name'] = isset($team['created_by']) ? $this->getUserName($team['created_by']) : 'Unknown';
            $team['players'] = json_decode($team['players'], true) ?: [];
        }
        $auth = service('authorization', false);
        if ($auth && method_exists($auth, 'inGroup')) {
            $canEdit = $auth->inGroup('coach');
        } else {
            $canEdit = (session('role_name') === 'coach' || session('role_id') == 2);
        }
        return view('admin/teams/index', ['teams' => $teams, 'canEdit' => $canEdit]);
    }

    // View a single team selection
    public function view($hash)
    {
        $id = $this->decodeHash($hash);
        $team = $this->teamModel->find($id);
        if (!$team) {
            return redirect()->to('/admin/teams')->with('error', 'Team not found.');
        }
        $fixture = $this->fixtureModel->find($team['fixture_id']);
        $team['fixture_name'] = $fixture ? ($fixture['team'] . ' vs ' . $fixture['opponent'] . ' @ ' . ($fixture['venue_name'] ?? $fixture['venue'] ?? '')) : 'Unknown';

        $team['coach1_name'] = isset($team['coach1_id']) ? $this->getUserName($team['coach1_id']) : '';
        $team['coach2_name'] = isset($team['coach2_id']) ? $this->getUserName($team['coach2_id']) : '';
        $team['manager_name'] = isset($team['manager_id']) ? $this->getUserName($team['manager_id']) : '';
        $playerIds = json_decode($team['players'], true) ?: [];
        // Fetch player names for each ID
        $playerModel = new UserModel();
        $players = [];
        if (!empty($playerIds)) {
            $playerRows = $playerModel->whereIn('id', $playerIds)->findAll();
            $playerMap = [];
            foreach ($playerRows as $p) {
                $photo = $p['photo'];
                $playerId = $p['id'];
                // If photo is empty, use default avatar
                if (empty($photo)) {
                    $photo = '/uploads/defaults/avatar.png';
                } elseif (is_numeric($playerId) && file_exists(FCPATH . 'uploads/players/' . $playerId . '/avatar.jpg')) {
                    $photo = '/uploads/players/' . $playerId . '/avatar.jpg';
                } elseif (strpos($photo, '/uploads/') !== 0) {
                    $photo = '/uploads/players/' . $playerId . '/avatar.jpg';
                }
                $playerMap[$p['id']] = [
                    'id' => $p['id'],
                    'name' => $p['name'],
                    'photo' => $photo,
                ];
            }
            foreach ($playerIds as $pid) {
                if (isset($playerMap[$pid])) {
                    $players[] = $playerMap[$pid];
                } else {
                    $players[] = [
                        'name' => $pid,
                        'photo' => '/uploads/defaults/avatar.png',
                    ];
                }
            }
        }
        $team['players'] = $players;
        $auth = service('authorization', false);
        if ($auth && method_exists($auth, 'inGroup')) {
            $canEdit = $auth->inGroup('coach');
        } else {
            $canEdit = (session('role_name') === 'coach' || session('role_id') == 2);
        }
        return view('admin/teams/view', ['team' => $team, 'canEdit' => $canEdit]);
    }

    // Create a new team selection (GET/POST)
    public function create($hash)
    {
        $fixture_id = $this->decodeHash($hash);
        $fixtures = $this->fixtureModel->orderBy('match_date', 'DESC')->findAll();
        $positions = require APPPATH . 'Config/RugbyPositions.php';
        $playerModel = new UserModel();
        $players = $playerModel->where('role_id', 3)->where('active', 1)->orderBy('name', 'ASC')->findAll();
        $coachModel = new UserModel();
        $coaches = $coachModel->where('role_id', 2)->where('active', 1)->orderBy('name', 'ASC')->findAll();
        $managerModel = new UserModel();
        $managers = $managerModel->where('role_id', 7)->where('active', 1)->orderBy('name', 'ASC')->findAll();
        $fixture = $this->fixtureModel->find($fixture_id);
        $defaultTeamName = $fixture ? ($fixture['team'] . ' vs ' . $fixture['opponent']) : '';
        if (strtolower($this->request->getMethod()) === 'post') {
            $playersInput = $this->request->getPost('players');
            $coach1_id = $this->request->getPost('coach1');
            $coach2_id = $this->request->getPost('coach2');
            $manager_id = $this->request->getPost('manager');
            $now = date('Y-m-d H:i:s');
            $data = [
                'fixture_id' => $this->request->getPost('fixture_id') ?: $fixture_id,
                'team_name' => $this->request->getPost('team_name'),
                'players' => json_encode($playersInput),
                'coach1_id' => $coach1_id,
                'coach2_id' => $coach2_id,
                'manager_id' => $manager_id,
                'notes' => $this->request->getPost('notes'),
                'created_by' => session('user_id'),
                'updated_by' => session('user_id'),
                'created_at' => $now,
                'updated_at' => $now,
            ];
            if ($this->teamModel->insert($data)) {
                return redirect()->to('/admin/teams')->with('success', 'Team created successfully.');
            } else {
                $errors = $this->teamModel->errors();
                return view('admin/teams/form', [
                    'fixtures' => $fixtures,
                    'positions' => $positions,
                    'players' => $players,
                    'coaches' => $coaches,
                    'managers' => $managers,
                    'team' => $data,
                    'errors' => $errors,
                ]);
            }
        }
        return view('admin/teams/form', [
            'fixtures' => $fixtures,
            'positions' => $positions,
            'players' => $players,
            'coaches' => $coaches,
            'managers' => $managers,
            'team' => [
                'team_name' => $defaultTeamName,
                'fixture_id' => $fixture_id,
            ],
        ]);
    }

    // Edit a team selection (GET/POST)
    public function edit($hash)
    {
        $id = $this->decodeHash($hash);
        $team = $this->teamModel->find($id);
        if (!$team) {
            return redirect()->to('/admin/teams')->with('error', 'Team not found.');
        }
        $fixtures = $this->fixtureModel->orderBy('match_date', 'DESC')->findAll();
        $team['players'] = json_decode($team['players'], true) ?: [];
        $coachModel = new UserModel();
        $coaches = $coachModel->where('role_id', 2)->where('active', 1)->orderBy('name', 'ASC')->findAll();
        $managerModel = new UserModel();
        $managers = $managerModel->where('role_id', 7)->where('active', 1)->orderBy('name', 'ASC')->findAll();
        if (strtolower($this->request->getMethod()) === 'post') {
            $positions = require APPPATH . 'Config/RugbyPositions.php';
            $players = $this->request->getPost('players');
            $coach1_id = $this->request->getPost('coach1');
            $coach2_id = $this->request->getPost('coach2');
            $manager_id = $this->request->getPost('manager');
            $now = date('Y-m-d H:i:s');
            $data = [
                'fixture_id' => $this->request->getPost('fixture_id') ?: $team['fixture_id'],
                'team_name' => $this->request->getPost('team_name'),
                'players' => json_encode($players),
                'coach1_id' => $coach1_id,
                'coach2_id' => $coach2_id,
                'manager_id' => $manager_id,
                'notes' => $this->request->getPost('notes'),
                'updated_by' => session('user_id'),
                'updated_at' => $now,
            ];
            if ($this->teamModel->update($id, $data)) {
                return redirect()->to('/admin/teams')->with('success', 'Team updated successfully.');
            } else {
                $errors = $this->teamModel->errors();
                return view('admin/teams/form', [
                    'fixtures' => $fixtures,
                    'positions' => $positions,
                    'players' => $players,
                    'coaches' => $coaches,
                    'managers' => $managers,
                    'team' => array_merge($team, $data),
                    'errors' => $this->teamModel->errors(),
                ]);
            }
        }
        $positions = require APPPATH . 'Config/RugbyPositions.php';
        $playerModel = new UserModel();
        $players = $playerModel->where('role_id', 3)->where('active', 1)->orderBy('name', 'ASC')->findAll();
        return view('admin/teams/form', [
            'fixtures' => $fixtures,
            'positions' => $positions,
            'players' => $players,
            'coaches' => $coaches,
            'managers' => $managers,
            'team' => $team,
        ]);
    }

    // Delete a team selection (POST)
    public function delete($hash)
    {
        $id = $this->decodeHash($hash);
        if ($this->teamModel->delete($id)) {
            return redirect()->to('/admin/teams')->with('success', 'Team deleted.');
        }
        return redirect()->to('/admin/teams')->with('error', 'Could not delete team.');
    }

    // Print team as PDF (with dompdf)
    public function printPdf($hash)
    {
        $id = $this->decodeHash($hash);
        $team = $this->teamModel->find($id);
        if (!$team) {
            return 'Team not found.';
        }
        $fixture = $this->fixtureModel->find($team['fixture_id']);
        $team['fixture_name'] = $fixture ? ($fixture['team'] . ' vs ' . $fixture['opponent'] . ' @ ' . ($fixture['venue_name'] ?? $fixture['venue'] ?? '')) : 'Unknown';
        $team['coach1_name'] = isset($team['coach1_id']) ? $this->getUserName($team['coach1_id']) : '';
        $team['coach2_name'] = isset($team['coach2_id']) ? $this->getUserName($team['coach2_id']) : '';
        $team['manager_name'] = isset($team['manager_id']) ? $this->getUserName($team['manager_id']) : '';
        $team['players'] = json_decode($team['players'], true) ?: [];
        // Fetch player names for each ID for PDF
        $playerIds = $team['players'];
        $playerModel = new UserModel();
        $players = [];
        if (!empty($playerIds)) {
            $playerRows = $playerModel->whereIn('id', $playerIds)->findAll();
            $playerMap = [];
            foreach ($playerRows as $p) {
                $playerMap[$p['id']] = $p['name'];
            }
            foreach ($playerIds as $pid) {
                $players[] = isset($playerMap[$pid]) ? $playerMap[$pid] : $pid;
            }
        }
        $team['players'] = $players;
        // Render HTML view
        $html = view('admin/teams/print', ['team' => $team], ['saveData' => false]);
        // Generate PDF
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $pdfOutput = $dompdf->output();
        $pdfFileName = 'team-sheet-' . ($team['fixture_name'] ? preg_replace('/[^a-zA-Z0-9-_]+/', '_', $team['fixture_name']) : $id) . '.pdf';
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'inline; filename="' . $pdfFileName . '"')
            ->setBody($pdfOutput);
    }

    // Helper to get user name by ID (real lookup)
    protected function getUserName($userId)
    {
        if (!$userId) return '';
        $userModel = new UserModel();
        $user = $userModel->find($userId);
        if ($user) {
            return $user['name'] . (isset($user['surname']) ? ' ' . $user['surname'] : '');
        }
        return 'Coach #' . $userId;
    }
}
