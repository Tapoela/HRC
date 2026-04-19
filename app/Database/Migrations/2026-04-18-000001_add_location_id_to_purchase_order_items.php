<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLocationIdToPurchaseOrderItems extends Migration
{
    public function up()
    {
        $this->forge->addColumn('purchase_order_items', [
            'location_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => null,
                'after'      => 'received_qty',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('purchase_order_items', 'location_id');
    }
}
