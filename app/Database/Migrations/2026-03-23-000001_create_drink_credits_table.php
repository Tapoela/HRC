<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDrinkCreditsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'total_bought' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'total_redeemed' => [
                'type' => 'INT',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('drink_credits');
    }

    public function down()
    {
        $this->forge->dropTable('drink_credits');
    }
}
