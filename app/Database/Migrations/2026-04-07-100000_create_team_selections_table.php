<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTeamSelectionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'           => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'fixture_id'   => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'team_name'    => ['type' => 'VARCHAR', 'constraint' => 100],
            'position'     => ['type' => 'VARCHAR', 'constraint' => 30], // e.g. 1, 2, 3, 9, 10, 15, etc.
            'player_name'  => ['type' => 'VARCHAR', 'constraint' => 100],
            'is_reserve'   => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at'   => ['type' => 'DATETIME', 'null' => true],
            'updated_at'   => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['fixture_id', 'position']);
        $this->forge->createTable('team_selections');
    }

    public function down()
    {
        $this->forge->dropTable('team_selections');
    }
}
