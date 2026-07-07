<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PropostasLogModel;

class PropostasLog extends BaseController
{
    public function index($id = null)
    {
        return view('auditoria/auditoria_list', ['pagina_titulo' => 'Auditoria', 'proposta_id' => $id]);
    }

    public function auditoria($id = null){
        $propostasLogModel = new PropostasLogModel();
        $pLogs = $propostasLogModel->where("proposta_id", $id)->findAll();

        return json_encode(['status' => 200, 'data' => $pLogs]);
    }
}
