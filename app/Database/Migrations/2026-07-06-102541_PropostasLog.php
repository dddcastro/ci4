<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class PropostasLog extends Migration
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
            'proposta_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'actor' => [
                'type' => 'VARCHAR',
                'constraint' => 25,
            ],
            'evento' => [
                'type' => 'ENUM',
                'constraint' => ['CREATED', 'UPDATED_FIELDS', 'STATUS_CHANGED', 'DELETED_LOGICAL'],
            ],
            'payload' => [
                'type' => 'TEXT',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP')
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('proposta_id', 'propostas', 'id');
        $this->forge->createTable('propostas_log');
    }

    public function down()
    {
        $this->forge->dropTable('propostas_log');
    }
}
