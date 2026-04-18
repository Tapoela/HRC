<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateEventsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'title'       => ['type' => 'VARCHAR', 'constraint' => 200],
            'description' => ['type' => 'TEXT', 'null' => true],
            'start'       => ['type' => 'DATETIME'],
            'end'         => ['type' => 'DATETIME', 'null' => true],
            'type'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'location'    => ['type' => 'VARCHAR', 'constraint' => 120, 'null' => true],
            'created_at'  => ['type' => 'DATETIME', 'null' => true],
            'updated_at'  => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('events');
    }

    public function down()
    {
        $this->forge->dropTable('events');
    }
}
