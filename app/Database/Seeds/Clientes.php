<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

class Clientes extends Seeder
{
    public function run()
    {
        $faker = Factory::create('pt_BR');
        for ($i = 0; $i < 100; $i++):
            //insere o cliente
            $clienteData = [
                'nome' => $faker->name,
                'email' => $faker->email,
                'documento' => $faker->cpf,
            ];
            $this->db->table('clientes')->insert($clienteData);

            //insere a proposta
            $clienteId = $this->db->insertID();
            $propostaData = [
                'cliente_id' => $clienteId,
                'produto' => $faker->word,
                'valor_mensal' => $faker->randomFloat(2, 100, 1000),
                'status' => $faker->randomElement(['DRAFT']),
                'origem' => $faker->randomElement(['APP', 'SITE', 'API']),
                'versao' => $faker->numberBetween(1),
            ];
            $this->db->table('propostas')->insert($propostaData);
            $propostaId = $this->db->insertID();
            //insere o log da proposta
            $propostaLogData = [
                'proposta_id' => $propostaId,
                'actor' => 'API',
                'evento' => 'CREATED',
                'payload' => json_encode($propostaData),
            ];
            $this->db->table('propostas_log')->insert($propostaLogData);
        endfor;
    }
}
