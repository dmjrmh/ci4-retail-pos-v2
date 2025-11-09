<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class DiscountDetail extends Migration
{
  public function up()
  {
    $this->forge->addField([
      'id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
      'discount_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
      'PCode'       => ['type' => 'VARCHAR', 'constraint' => 20],
      'type'        => ['type' => 'ENUM', 'constraint' => ['P', 'R'], 'default' => 'P'],
      'value'       => ['type' => 'DECIMAL', 'constraint' => '10,3', 'default' => '0.000'],
      'created_at'  => ['type' => 'DATETIME', 'null' => true],
      'updated_at'  => ['type' => 'DATETIME', 'null' => true],
      'deleted_at'  => ['type' => 'DATETIME', 'null' => true],
    ]);
    
    $this->forge->addKey('id', true);
    $this->forge->addUniqueKey(['discount_id', 'PCode']);
    $this->forge->addForeignKey('discount_id', 'discount_headers', 'id', 'CASCADE', 'CASCADE');
    $this->forge->addForeignKey('PCode', 'masterbarang', 'PCode', 'CASCADE', 'CASCADE');
    $this->forge->createTable('discount_details', true);
  }

  public function down()
  {
    $this->forge->dropTable('discount_details', true);
  }
}
