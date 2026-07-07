<?= $this->extend("master.php")?>

<?=$this->section('content')?>
    <div class="container-xxl">
        <h1>PROPOSTAS<a ahef="<?= base_url('/propostas/cadastrar')?>" class="btn btn-info" style="float: right; margin-top:1rem;">Cadastrar</a></h1>
        <hr/>

        <table class="table table-dark">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">cliente</th>
            <th scope="col">produto</th>
            <th scope="col">valor_mensal</th>
            <th scope="col">status</th>
            <th scope="col">origem</th>
            <th scope="col" class="text-center">ações</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
        </table>
        <div class='text-center table-footer' style="padding-left: 2px"></div>
    </div>

    <script>
        $(function () {
            $.ajax({
                url: '<?= base_url('/api/v1/propostas/?page=' . $page) ?>',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if(response.total > 1){
                        let propostas = response.data;
                        $.map(propostas, function(proposta, index){
                            let html = '<tr>';
                            html += '<td>' + proposta.id + '</td>';
                            html += '<td>' + proposta.cliente_nome + '</td>';
                            html += '<td>' + proposta.produto + '</td>';
                            html += '<td>' + proposta.valor_mensal + '</td>';
                            html += '<td>' + proposta.status + '</td>';
                            html += '<td>' + proposta.origem + '</td>';

                            html += '<td class=\'text-center\'>';
                            html += '<a href=\'<?= base_url('propostas/editar/') ?>' + proposta.id + '\' type=\'button\' class=\'btn btn-info\'>editar</a>';
                            html += '<a href=\'<?= base_url('propostas/') ?>' + proposta.id + '/auditoria' + '\' type=\'button\' class=\'btn btn-info\'>auditoria</a></td>';
                            html +=    '</tr>';
                            $('tbody').append(html);
                        });
                        let footer = '';
                        for(let i = 1; i <= response.pagina_final; i++){
                            if(i != response.pagina_atual)
                                footer += '<a href=\'<?= base_url('/propostas') ?>?page=' + i + '\' class=\'btn btn-dark\'>'+i+'</a>';
                            else
                                footer += '<a class=\'btn btn-dark disabled\'>'+i+'</a>';
                        }
                        $('.table-footer').append(footer);
                    }else{
                        $('tbody').append("<tr><td colspan='5'>Nenhuma proposta cadastrada.</td></tr>");
                    }
                },
            });
        });
    </script>
<?=$this->endSection()?> ?>