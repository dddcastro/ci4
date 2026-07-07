<?= $this->extend("master.php")?>

<?=$this->section('content')?>
    <div class="container-xxl">
        <h1><?= $pagina_titulo ?></h1>
        <hr />
        <input type="hidden" name="proposta_id" id="proposta_id" value="<?= $proposta_id ?>">
        <input type="hidden" name="proposta_versao" id="proposta_versao" value="1">
        <div class="mb-3">
            <label for="proposta_cliente_id" class="form-label">Cliente</label>
            <select id="proposta_cliente_id" name="proposta_cliente_id" class="form-control selectpicker" data-live-search="true" title="Selecione um cliente">
            </select>
        </div>
        <div class="mb-3">
            <label for="proposta_produto" class="form-label">Produto</label>
            <input type="text" name="proposta_produto" class="form-control" id="proposta_produto">
        </div>
        <div class="mb-3">
            <label for="proposta_valor_mensal" class="form-label">valor mensal</label>
            <input type="number" name="proposta_valor_mensal" class="form-control" id="proposta_valor_mensal" step="0.01" min="0">
        </div>
        <div class="mb-3">
            <label for="proposta_origem" class="form-label">origem</label>
            <select id="proposta_origem" name="proposta_origem" class="form-control">
                <option value="APP">APP</option>
                <option value="SITE">SITE</option>
                <option value="API">API</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="proposta_status" class="form-label">status</label>
            <select id="proposta_status" name="proposta_status" class="form-control">
                <option value="DRAFT">DRAFT</option>
            </select>
        </div>
        <div class="mb-3 text-right">
            <a class="btn btn-primary" type="button" onclick="cadastra_edita()">Salvar</a> ou <a href="<?= base_url('/propostas')?> ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
        <script>
            let listaStatus = ['DRAFT', 'SUBMITTED', 'APPROVED', 'REJECTED', 'CANCELED'];
            <?php if(isset($proposta_id)): ?>
            $(function () {
                $.ajax({
                    url: '<?= base_url('/api/v1/clientes?selectform=true') ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if(response.status == 200){
                            let option = '';
                            $.map(response.data, function(cliente, index){
                                option += '<option value="' + cliente.id + '">' + cliente.nome + '</option>';
                            });
                            $('#proposta_cliente_id').append(option);
                            $('#proposta_cliente_id').selectpicker('refresh');
                        }
                    },
                })
                .done(function(){
                    preenche_form();
                });

                function preenche_form(){
                    $.ajax({
                        url: '<?= base_url('/api/v1/propostas/' . $proposta_id) ?>',
                        type: 'GET',
                        dataType: 'json',
                        success: function (response) {
                            if(response.status == 200){
                                let proposta = response.data;
                                let option = "";
                                if(proposta.status == "DRAFT"){
                                    option    += '<option value="DRAFT" selected>DRAFT</option>';
                                    option    += '<option value="SUBMITTED">SUBMITTED</option>';
                                    option    += '<option value="CANCELED">CANCELED</option>';
                                }else if(proposta.status == "SUBMITTED"){
                                    option    += '<option value="SUBMITTED" selected>SUBMITTED</option>';
                                    option    += '<option value="APPROVED">APPROVED</option>';
                                    option    += '<option value="REJECTED">REJECTED</option>';
                                    option    += '<option value="CANCELED">CANCELED</option>';
                                }
                                else if(proposta.status == "APPROVED"){
                                    option    += '<option value="APPROVED" selected>APPROVED</option>';
                                    option    += '<option value="CANCELED">CANCELED</option>';
                                }else if(proposta.status == "REJECTED"){
                                    option    += '<option value="REJECTED" selected>REJECTED</option>';
                                }
                                else if(proposta.status == "CANCELED"){
                                    option    += '<option value="CANCELED" selected>CANCELED</option>';
                                }

                                $('#proposta_status').html(option);

                                $('#proposta_cliente_id').selectpicker('val', proposta.cliente_id);
                                $('#proposta_produto').val(proposta.produto);
                                $('#proposta_valor_mensal').val(proposta.valor_mensal);
                                $('#proposta_origem').val(proposta.origem);
                                $('#proposta_versao').val(proposta.versao);
                            }
                        },
                    });
                }

            });

            <?php endif;?>

            function cadastra_edita(){
                let proposta_id = $('#proposta_id').val();
                let proposta_cliente_id = $('#proposta_cliente_id').val();
                let proposta_produto = $('#proposta_produto').val();
                let proposta_valor_mensal = $('#proposta_valor_mensal').val();
                let proposta_origem = $('#proposta_origem').val();
                let proposta_status = $('#proposta_status').val();
                let proposta_versao = $('#proposta_versao').val();

                //rotina cadastra
                if(proposta_id === ''){
                    console.log('cadastra');
                    $.ajax({
                        url: '<?= base_url('/api/v1/propostas') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            proposta_cliente_id: proposta_cliente_id,
                            proposta_produto: proposta_produto,
                            proposta_valor_mensal: proposta_valor_mensal,
                            proposta_origem: proposta_origem,
                            proposta_status: proposta_status,
                            proposta_versao: proposta_versao,
                        },
                        success: function (response) {
                            alert(response.msg);
                            window.location.replace("/propostas");
                        },
                    });
                }
                //rotina altera
                else{
                    console.log('edita');
                    $.ajax({
                        url: '<?= base_url('/api/v1/propostas/' . $proposta_id) ?>',
                        type: 'PATCH',
                        dataType: 'json',
                        data: {
                            proposta_id: proposta_id,
                            proposta_cliente_id: proposta_cliente_id,
                            proposta_produto: proposta_produto,
                            proposta_valor_mensal: proposta_valor_mensal,
                            proposta_origem: proposta_origem,
                            proposta_status: proposta_status,
                            proposta_versao: proposta_versao,
                        },
                        success: function (response) {
                            alert(response.msg);
                            window.location.replace("/propostas");
                        },
                    });
                }
            }
        </script>
<?=$this->endSection()?> ?>