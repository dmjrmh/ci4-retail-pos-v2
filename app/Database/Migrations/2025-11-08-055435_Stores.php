<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Stores extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'         => ['type' => 'INT', 'unsigned' => true, 'auto_increment' => true],
      'store_code' => ['type' => 'VARCHAR', 'constraint' => 20],
      'name'       => ['type' => 'VARCHAR', 'constraint' => 100],
      'address'    => ['type' => 'TEXT', 'null' => true],
      'city'       => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
      'created_at' => ['type' => 'DATETIME', 'null' => true],
      'updated_at' => ['type' => 'DATETIME', 'null' => true],
      'deleted_at' => ['type' => 'DATETIME', 'null' => true],
    ]);

    $this->forge->addKey('id');
    $this->forge->addUniqueKey('store_code', 'uq_store_code');
    $this->forge->createTable('stores', true);
  }

  public function down()
  {
    $this->forge->dropTable('stores', true);
  }
}
