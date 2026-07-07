<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ClientesModel;

class Clientes extends BaseController
{
    public function index()
    {
        $page = $this->request->getGet('page');
        if(!is_numeric($page))
            $page = 1;
        return view("clientes/clientes_list", ['pagina_titulo' => 'Clientes', 'page' => $page]);
    }
    public function form($id = null){
        if($id === null)
            return view("clientes/clientes_form", ['pagina_titulo' => 'Cadastrar Cliente', 'cliente_id' => '']);
        else
            return view("clientes/clientes_form", ['pagina_titulo' => 'Editar Cliente', 'cliente_id' => $id]);
    }

    public function list(){
        $clientesModel = new ClientesModel();
        if($this->request->getGet('selectform')){
            $clientes = $clientesModel->findAll();

            $data = [
                'status' => 200,
                'data' => $clientes,
            ];

            return json_encode($data);
        }else
            $clientes = $clientesModel->paginate(10);

        $pager = $clientesModel->pager;
        $data = [
            'status' => 200,
            'data' => $clientes,
            'total' => $pager->getTotal(),
            'pagina_atual' => $pager->getCurrentPage(),
            'pagina_final' => $pager->getPageCount(),
        ];

        return json_encode($data);
    }

    public function details(int $id){
        $clienteModel = new ClientesModel();
        $cliente = $clienteModel->find($id);

        if($cliente === null)
            return json_encode(['status' => 404, 'msg' => 'usuario não encontrado.']);
        else
            return json_encode(['status' => 200, 'data' => $cliente]);
    }

    public function save($id = null){
        $clienteModel = new ClientesModel();
        if(is_numeric($id)){
            $data = $this->request->getRawInput();
            $cliente = [
                'nome' => $data['cliente_nome'],
                'email' => $data['cliente_email'],
                'documento' => $data['cliente_documento'],
            ];
            try{
                $clienteModel->update($data['cliente_id'], $cliente);
            }catch(\Exception $e){
                return $e->getMessage();
            }

            return json_encode(['status' => 200, 'msg' => 'Cliente editado com sucesso!']);
        }else{
            $data = $this->request->getPost();
            $cliente = [
                'nome' => $data['cliente_nome'],
                'email' => $data['cliente_email'],
                'documento' => $data['cliente_documento'],
            ];
            try{
                $clienteModel->insert($cliente);
            }catch(\Exception $e){
                return $e->getMessage();
            }

            return json_encode(['status' => 201, 'msg' => 'Cliente cadastrado com sucesso!']);
        }
    }
}
