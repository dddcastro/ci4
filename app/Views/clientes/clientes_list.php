<?= $this->extend("master.php")?>

<?=$this->section('content')?>
    <div class="container-xxl">
        <h1>CLIENTES<a ahef="<?= base_url('/clientes/cadastrar')?>" class="btn btn-info" style="float: right; margin-top:1rem;">Cadastrar</a></h1>
        <hr/>

        <table class="table table-dark">
        <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">nome</th>
            <th scope="col">email</th>
            <th scope="col">documento</th>
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
                url: '<?= base_url('/api/v1/clientes/?page=' . $page) ?>',
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if(response.total > 1){
                        let clientes = response.data;
                        $.map(clientes, function(cliente, index){
                            let html = '<tr>';
                            html += '<td>' + cliente.id + '</td>';
                            html += '<td>' + cliente.nome + '</td>';
                            html += '<td>' + cliente.email + '</td>';
                            html += '<td>' + cliente.documento + '</td>';
                            html += '<td class=\'text-center\'><a href=\'<?= base_url('clientes/editar/') ?>' + cliente.id + '\' type=\'button\' class=\'btn btn-info\'>editar</a></td>';
                            html +=    '</tr>';
                            $('tbody').append(html);
                        });
                        let footer = '';
                        for(let i = 1; i <= response.pagina_final; i++){
                            if(i != response.pagina_atual)
                                footer += '<a href=\'<?= base_url('/clientes') ?>?page=' + i + '\' class=\'btn btn-dark\'>'+i+'</a>';
                            else
                                footer += '<a class=\'btn btn-dark disabled\'>'+i+'</a>';
                        }
                        $('.table-footer').append(footer);
                    }else{
                        $('tbody').append("<tr><td colspan='5'>Nenhum cliente cadastrado.</td></tr>");
                    }
                },
            });
        });
    </script>
<?=$this->endSection()?> ?>