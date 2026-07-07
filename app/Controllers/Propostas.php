<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PropostasModel;
use App\Models\PropostasLogModel;
use Exception;

use function PHPUnit\Framework\throwException;

class Propostas extends BaseController
{
    public function index()
    {
        $page = $this->request->getGet('page');
        if(!is_numeric($page))
            $page = 1;
        return view("propostas/propostas_list", ['pagina_titulo' => 'Propostas', 'page' => $page]);
    }
    public function form($id = null){
        if($id === null)
            return view("propostas/propostas_form", ['pagina_titulo' => 'Cadastrar Proposta', 'proposta_id' => '']);
        else
            return view("propostas/propostas_form", ['pagina_titulo' => 'Editar Proposta', 'proposta_id' => $id]);
    }

    public function list(){
        $propostasModel = new PropostasModel();
        $propostas = $propostasModel->select('propostas.id, cliente_id, produto, valor_mensal, origem, versao, status, clientes.nome as cliente_nome')
        ->join('clientes', 'propostas.cliente_id = clientes.id' )
        ->where('propostas.deleted_at IS NULL', null, false)
        ->paginate(10);
        $pager = $propostasModel->pager;
        $data = [
            'status' => 200,
            'data' => $propostas,
            'total' => $pager->getTotal(),
            'pagina_atual' => $pager->getCurrentPage(),
            'pagina_final' => $pager->getPageCount(),
        ];

        return json_encode($data);
    }

    public function details(int $id){
        $propostasModel = new PropostasModel();
        $propostas = $propostasModel->select('propostas.id, cliente_id, produto, valor_mensal, origem, versao, status, clientes.nome as cliente_nome')
        ->join('clientes', 'propostas.cliente_id = clientes.id')
        ->find($id);


        if($propostas === null)
            return json_encode(['status' => 404, 'msg' => 'proposta não encontrada.']);
        else
            return json_encode(['status' => 200, 'data' => $propostas]);
    }

    public function save($id = null){
        $propostasModel = new PropostasModel();
        if(is_numeric($id)){
            $old = $propostasModel->where('id', $id)->findAll();
            $data = $this->request->getRawInput();
            $proposta = [
                'cliente_id' => $data['proposta_cliente_id'],
                'produto' => $data['proposta_produto'],
                'valor_mensal' => $data['proposta_valor_mensal'],
                'origem' => $data['proposta_origem'],
                'status' => $data['proposta_status'],
                'versao' => $data['proposta_versao'] + 1,
            ];
            try{
                if($old['versao'] != $data['proposta_versao'])
                    throw new Exception('Erro de versão');

                $propostasModel->update($data['proposta_id'], $proposta);
                $new = $propostasModel->where('id', $id)->findAll();
                $this->newLog($old[0], $new[0]);
            }catch(\Exception $e){
                return $e->getMessage();
            }

            return json_encode(['status' => 200, 'msg' => 'Proposta editada com sucesso!']);
        }else{
            $data = $this->request->getPost();
            $proposta = [
                'cliente_id' => $data['proposta_cliente_id'],
                'produto' => $data['proposta_produto'],
                'valor_mensal' => $data['proposta_valor_mensal'],
                'origem' => $data['proposta_origem'],
                'status' => $data['proposta_status'],
            ];
            try{
                $propostasModel->insert($proposta);
                $id = $propostasModel->getInsertID();
                $new = $propostasModel->where('id', $id)->findAll();
                $this->newLog([], $new[0]);
            }catch(\Exception $e){
                return $e->getMessage();
            }

            return json_encode(['status' => 201, 'msg' => 'Proposta cadastrada com sucesso!']);
        }
    }
    public function newLog($old = array(), $new = array()){
        $propostaLogModel = new PropostasLogModel();
        $data = [
            'proposta_id' => $new['id'],
            'actor' => 1,
        ];
        if(empty($old))
            $data['evento'] = "CREATED";
        else if(
            $old['produto'] != $new['produto'] ||
            $old['valor_mensal'] != $new['valor_mensal'] ||
            $old['origem'] != $new['origem']
        )
            $data['evento'] = "UPDATED_FIELDS";

        $payload = [];
        if($old['produto'] != $new['produto'])
            $payload['produto'] = ['antigo' => $old['produto'], 'novo' => $new['produto']];

        if($old['valor_mensal'] != $new['valor_mensal'])
            $payload['valor_mensal'] = ['antigo' => $old['valor_mensal'], 'novo' => $new['valor_mensal']];

        if($old['origem'] != $new['origem'])
            $payload['origem'] = ['antigo' => $old['origem'], 'novo' => $new['origem']];

        if(!empty($payload)){
            $data['payload'] = json_encode($payload);
            //INSERE LOG DE ALTERAÇÂO
            $propostaLogModel->insert($data);
        }

        //SEPARA A ALTERAÇÂO DE CAMPOS DE STATUS CHANGED POR SER 2 GATILHOS
        if($old['status'] != $new['status']){
            $data['evento'] = "STATUS_CHANGED";

            $payload = [];
            $payload['status'] = ['antigo' => $old['status'], 'novo' => $new['status']];
            $data['payload'] = json_encode($payload);

            $propostaLogModel->insert($data);
        }
    }
}
