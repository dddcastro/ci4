<?= $this->extend("master.php")?>

<?=$this->section('content')?>
    <div class="container-xxl">
        <h1><?= $pagina_titulo ?></h1>
        <hr />
        <input type="hidden" name="cliente_id" id="cliente_id" value="<?= $cliente_id ?>">
        <div class="mb-3">
            <label for="cliente_nome" class="form-label">Nome</label>
            <input type="text" name="cliente_nome" class="form-control" id="cliente_nome">
        </div>
        <div class="mb-3">
            <label for="cliente_email" class="form-label">E-mail</label>
            <input type="text" name="cliente_email" class="form-control" id="cliente_email">
        </div>
        <div class="mb-3">
            <label for="cliente_documento" class="form-label">Documento</label>
            <input type="text" name="cliente_documento" class="form-control" id="cliente_documento">
        </div>
        <div class="mb-3 text-right">
            <a class="btn btn-primary" type="button" onclick="cadastra_edita()">Salvar</a> ou <a href="<?= base_url('/clientes')?> ?>" class="btn btn-secondary">Cancelar</a>
        </div>
    </div>
        <script>
            <?php if(isset($cliente_id)): ?>
            $(function () {
                $.ajax({
                    url: '<?= base_url('/api/v1/clientes/' . $cliente_id) ?>',
                    type: 'GET',
                    dataType: 'json',
                    success: function (response) {
                        if(response.status == 200){
                            let cliente = response.data;
                            $('#cliente_nome').val(cliente.nome);
                            $('#cliente_email').val(cliente.email);
                            $('#cliente_documento').val(cliente.documento);
                        }
                    },
                });
            });

            <?php endif;?>

            function cadastra_edita(){
                let cliente_id = $('#cliente_id').val();
                let cliente_nome = $('#cliente_nome').val();
                let cliente_email = $('#cliente_email').val();
                let cliente_documento = $('#cliente_documento').val();

                let doc = processarCpfCnpj(cliente_documento)
                if(doc.valido){
                    cliente_documento = doc.formatado;
                }else{
                    alert("Documento invalido");
                    return false;
                }

                //rotina cadastra
                if(cliente_id === ''){
                    console.log('cadastra');
                    $.ajax({
                        url: '<?= base_url('/api/v1/clientes') ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {
                            cliente_nome: cliente_nome,
                            cliente_email: cliente_email,
                            cliente_documento: cliente_documento,
                        },
                        success: function (response) {
                            alert(response.msg);
                            window.location.replace("/clientes");
                        },
                    });
                }
                //rotina altera
                else{
                    console.log('edita');
                    $.ajax({
                        url: '<?= base_url('/api/v1/clientes/' . $cliente_id) ?>',
                        type: 'PATCH',
                        dataType: 'json',
                        data: {
                            cliente_id: cliente_id,
                            cliente_nome: cliente_nome,
                            cliente_email: cliente_email,
                            cliente_documento: cliente_documento,
                        },
                        success: function (response) {
                            alert(response.msg);
                            window.location.replace("/clientes");
                        },
                    });
                }


            }

            function processarCpfCnpj(valor) {
                // Remove caracteres não numéricos
                const numeros = valor.replace(/\D/g, '');

                if (numeros.length === 11) {
                    const valido = validarCpf(numeros);
                    return {
                        valido: valido,
                        tipo: 'CPF',
                        formatado: valido ? formatarCpf(numeros) : null
                    };
                } else if (numeros.length === 14) {
                    const valido = validarCnpj(numeros);
                    return {
                        valido: valido,
                        tipo: 'CNPJ',
                        formatado: valido ? formatarCnpj(numeros) : null
                    };
                } else {
                    return { valido: false, tipo: null, formatado: null };
                }
            }

            function formatarCpf(cpf) {
                return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
            }

            function formatarCnpj(cnpj) {
                return cnpj.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/, "$1.$2.$3/$4-$5");
            }

            function validarCpf(cpf) {
                if (/^(\d)\1+$/.test(cpf)) return false;
                let soma = 0, resto;
                for (let i = 1; i <= 9; i++) soma += parseInt(cpf.substring(i - 1, i)) * (11 - i);
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                if (resto !== parseInt(cpf.substring(9, 10))) return false;
                soma = 0;
                for (let i = 1; i <= 10; i++) soma += parseInt(cpf.substring(i - 1, i)) * (12 - i);
                resto = (soma * 10) % 11;
                if ((resto === 10) || (resto === 11)) resto = 0;
                if (resto !== parseInt(cpf.substring(10, 11))) return false;
                return true;
            }

            function validarCnpj(cnpj) {
                if (/^(\d)\1+$/.test(cnpj)) return false;
                let tamanho = cnpj.length - 2, numeros = cnpj.substring(0, tamanho), digitos = cnpj.substring(tamanho);
                let soma = 0, pos = tamanho - 7;
                for (let i = tamanho; i >= 1; i--) {
                    soma += parseInt(numeros.charAt(tamanho - i)) * pos--;
                    if (pos < 2) pos = 9;
                }
                let resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
                if (resultado !== parseInt(digitos.charAt(0))) return false;
                tamanho += 1; numeros = cnpj.substring(0, tamanho); soma = 0; pos = tamanho - 7;
                for (let i = tamanho; i >= 1; i--) {
                    soma += parseInt(numeros.charAt(tamanho - i)) * pos--;
                    if (pos < 2) pos = 9;
                }
                resultado = soma % 11 < 2 ? 0 : 11 - (soma % 11);
                return resultado === parseInt(digitos.charAt(1));
            }
        </script>
<?=$this->endSection()?> ?>