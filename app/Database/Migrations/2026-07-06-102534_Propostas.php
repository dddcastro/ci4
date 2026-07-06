<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;
use CodeIgniter\Database\RawSql;

class Propostas extends Migration
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
            'cliente_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'produto' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'valor_mensal' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED', 'CANCELLED'],
                'default' => 'DRAFT',
            ],
            'origem' => [
                'type' => 'ENUM',
                'constraint' => ['APP', 'SITE', 'API'],
                'default' => 'SITE',
            ],
            'versao' => [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => false,
                'default' => new RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',  
                'null' => true,
            ],
        ]);
        $this->forge->addPrimaryKey('id');
        $this->forge->addForeignKey('cliente_id', 'clientes', 'id');
        $this->forge->createTable('propostas');
    }

    public function down()
    {
        $this->forge->dropTable('propostas');
    }
}
