<?= $this->extend("master.php")?>

<?=$this->section('content')?>
    <div class="container-xxl">
        <h1>AUDITORIA PROPOSTA #<?= $proposta_id ?></h1>
        <hr/>

        <div class="mb-3">
            <label for="proposta_cliente_id" class="form-label">Cliente</label>
            <input type="text" name="proposta_cliente_id" class="form-control" id="proposta_cliente_id" readonly>
        </div>
        <div class="mb-3">
            <label for="proposta_produto" class="form-label">Produto</label>
            <input type="text" name="proposta_produto" class="form-control" id="proposta_produto" readonly>
        </div>
        <div class="mb-3">
            <label for="proposta_valor_mensal" class="form-label">valor mensal</label>
            <input type="text" name="proposta_valor_mensal" class="form-control" id="proposta_valor_mensal" readonly>
        </div>
        <div class="mb-3">
            <label for="proposta_origem" class="form-label">origem</label>
            <input type="text" name="proposta_origem" class="form-control" id="proposta_origem" readonly>
        </div>
        <div class="mb-3">
            <label for="proposta_status" class="form-label">status</label>
            <input type="text" name="proposta_status" class="form-control" id="proposta_status" readonly>
        </div>

        <h1>HISTORICO</h1>
        <hr/>

        <table class="table table-dark">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">evento</th>
            <th scope="col">payload</th>
            <th scope="col">data</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
    </div>

    <script>
        $(function () {
            $.ajax({
                url: '<?= base_url('/api/v1/propostas/' . $proposta_id) ?>',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    let proposta = response.data;
                    $('#proposta_cliente_id').val(proposta.cliente_nome);
                    $('#proposta_produto').val(proposta.produto);
                    $('#proposta_valor_mensal').val(proposta.valor_mensal);
                    $('#proposta_origem').val(proposta.origem); 
                    $('#proposta_status').val(proposta.status);                    
                },
            });

            $.ajax({
                url: '<?= base_url('/api/v1/propostas/' . $proposta_id . '/auditoria') ?>',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    let propostaLog = response.data;
                    $.map(propostaLog, function(pLog, index){
                        let html = '<tr>';
                        html += '<td>' + pLog.id + '</td>';
                        html += '<td>' + pLog.evento + '</td>';
                        html += '<td>' + pLog.payload + '</td>';
                        html += '<td>' + pLog.updated_at + '</td>';
                        html +=    '</tr>';
                        $('tbody').append(html);
                    });
                },
            });
        });
    </script>
<?=$this->endSection()?> ?>