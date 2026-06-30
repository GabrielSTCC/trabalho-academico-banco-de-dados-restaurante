<?php
include('conexao.php');
require_once 'includes/mesa_sync.php';

$pageTitle = 'Cadastrar Pedido';
$currentPage = 'cadastrar_pedido';
$pageDescription = 'Registrar pedido com cliente, mesa e itens do cardápio';
$sucesso = false;
$erro = '';
$idPedidoCriado = 0;
$totalItensCadastrados = 0;

$clientes = [];
$resultadoClientes = $conexao->query("SELECT id_cliente, nome FROM Cliente ORDER BY nome");
while ($linha = $resultadoClientes->fetch_assoc()) {
    $clientes[] = $linha;
}

$mesas = [];
$resultadoMesas = $conexao->query("SELECT id_mesa, numero, status FROM Mesa ORDER BY numero");
while ($linha = $resultadoMesas->fetch_assoc()) {
    $mesas[] = $linha;
}

$pratos = [];
$resultadoPratos = $conexao->query("SELECT id_prato, nome, categoria, preco FROM Prato ORDER BY categoria, nome");
while ($linha = $resultadoPratos->fetch_assoc()) {
    $pratos[] = $linha;
}

if (isset($_POST['id_cliente']) && isset($_POST['id_mesa'])) {

    $id_cliente = (int) $_POST['id_cliente'];
    $id_mesa = (int) $_POST['id_mesa'];
    $status = $_POST['status'] ?? 'aberto';

    $statusValidos = ['aberto', 'fechado', 'cancelado'];
    if (!in_array($status, $statusValidos, true)) {
        $status = 'aberto';
    }

    $itensValidos = [];
    if (isset($_POST['id_prato']) && is_array($_POST['id_prato'])) {
        foreach ($_POST['id_prato'] as $i => $id_prato) {
            $id_prato = (int) $id_prato;
            $quantidade = (int) ($_POST['quantidade'][$i] ?? 1);
            if ($id_prato > 0 && $quantidade >= 1) {
                $itensValidos[] = [
                    'id_prato'   => $id_prato,
                    'quantidade' => $quantidade,
                ];
            }
        }
    }

    if ($id_cliente <= 0 || $id_mesa <= 0) {
        $erro = 'Selecione um cliente e uma mesa válidos.';
    } elseif (empty($itensValidos)) {
        $erro = 'Adicione pelo menos um item ao pedido.';
    } else {
        $conexao->begin_transaction();

        $sqlPedido = "INSERT INTO Pedido (id_cliente, id_mesa, status)
                      VALUES ($id_cliente, $id_mesa, '$status')";

        if (!$conexao->query($sqlPedido)) {
            $conexao->rollback();
            $erro = 'Erro ao cadastrar pedido: ' . $conexao->error;
        } else {
            $id_pedido = $conexao->insert_id;
            $falhou = false;

            foreach ($itensValidos as $item) {
                $resPrato = $conexao->query(
                    "SELECT preco FROM Prato WHERE id_prato = {$item['id_prato']}"
                );

                if (!$resPrato || $resPrato->num_rows === 0) {
                    $conexao->rollback();
                    $erro = 'Prato selecionado não encontrado.';
                    $falhou = true;
                    break;
                }

                $preco = (float) $resPrato->fetch_assoc()['preco'];
                $subtotal = $item['quantidade'] * $preco;

                $sqlItem = "INSERT INTO Item_Pedido (id_pedido, id_prato, quantidade, subtotal)
                            VALUES ($id_pedido, {$item['id_prato']}, {$item['quantidade']}, $subtotal)";

                if (!$conexao->query($sqlItem)) {
                    $conexao->rollback();
                    $erro = 'Erro ao cadastrar itens: ' . $conexao->error;
                    $falhou = true;
                    break;
                }
            }

            if (!$falhou) {
                $conexao->commit();
                sincronizarStatusMesa($conexao, $id_mesa, $status);
                $sucesso = true;
                $idPedidoCriado = $id_pedido;
                $totalItensCadastrados = count($itensValidos);
            }
        }
    }
}

$itensFormulario = [];
if (!$sucesso && isset($_POST['id_prato']) && is_array($_POST['id_prato'])) {
    foreach ($_POST['id_prato'] as $i => $id_prato) {
        $itensFormulario[] = [
            'id_prato'   => $_POST['id_prato'][$i] ?? '',
            'quantidade' => $_POST['quantidade'][$i] ?? 1,
        ];
    }
}
if (empty($itensFormulario)) {
    $itensFormulario[] = ['id_prato' => '', 'quantidade' => 1];
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Cadastrar Pedido</h2>
    <p>Vincule o pedido a um cliente, selecione a mesa e adicione os pratos</p>
</div>

<?php if ($sucesso): ?>
    <div class="alert alert-success">
        Pedido #<?= $idPedidoCriado ?> cadastrado com sucesso!
        <?= $totalItensCadastrados ?> <?= $totalItensCadastrados === 1 ? 'item adicionado' : 'itens adicionados' ?>.
    </div>
<?php endif; ?>

<?php if ($erro): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<div class="card card-narrow">
    <form method="POST" id="form-pedido">
        <div class="form-group">
            <label for="id_cliente">Cliente</label>
            <select id="id_cliente" name="id_cliente" class="form-control" required>
                <option value="">Selecione um cliente</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= $cliente['id_cliente'] ?>"
                        <?= isset($_POST['id_cliente']) && !$sucesso && $_POST['id_cliente'] == $cliente['id_cliente'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cliente['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="id_mesa">Mesa</label>
            <select id="id_mesa" name="id_mesa" class="form-control" required>
                <option value="">Selecione uma mesa</option>
                <?php foreach ($mesas as $mesa): ?>
                    <option value="<?= $mesa['id_mesa'] ?>"
                        <?= isset($_POST['id_mesa']) && !$sucesso && $_POST['id_mesa'] == $mesa['id_mesa'] ? 'selected' : '' ?>>
                        Mesa #<?= $mesa['numero'] ?> (<?= $mesa['status'] ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <?php
                $statusSelecionado = (!$sucesso && isset($_POST['status'])) ? $_POST['status'] : 'aberto';
                foreach (['aberto', 'fechado', 'cancelado'] as $opcao):
                ?>
                    <option value="<?= $opcao ?>" <?= $statusSelecionado === $opcao ? 'selected' : '' ?>>
                        <?= ucfirst($opcao) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-section">
            <div class="form-section-header">
                <label>Itens do pedido</label>
                <button type="button" class="btn btn-secondary btn-sm" id="btn-adicionar-item">+ Adicionar prato</button>
            </div>

            <div id="itens-container">
                <?php foreach ($itensFormulario as $index => $item): ?>
                <div class="item-row">
                    <div class="form-group item-prato">
                        <label>Prato</label>
                        <select name="id_prato[]" class="form-control" <?= $index === 0 ? 'required' : '' ?>>
                            <option value="">Selecione um prato</option>
                            <?php foreach ($pratos as $prato): ?>
                                <option value="<?= $prato['id_prato'] ?>"
                                    <?= !$sucesso && (string) $item['id_prato'] === (string) $prato['id_prato'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($prato['nome']) ?> — R$ <?= number_format($prato['preco'], 2, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group item-quantidade">
                        <label>Qtd</label>
                        <input type="number" name="quantidade[]" class="form-control" min="1" value="<?= (int) $item['quantidade'] ?>">
                    </div>
                    <div class="form-group item-remover">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-secondary btn-sm btn-remover-item">Remover</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="listar_pedidos.php" class="btn btn-secondary">Ver listagem</a>
        </div>
    </form>
</div>

<style>
.form-section { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--color-border); }
.form-section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.form-section-header > label { font-weight: 600; font-size: 0.875rem; }
.item-row { display: flex; gap: 0.75rem; align-items: flex-end; margin-bottom: 0.75rem; }
.item-prato { flex: 1; margin-bottom: 0; }
.item-quantidade { width: 5rem; margin-bottom: 0; }
.item-remover { margin-bottom: 0; }
.btn-sm { padding: 0.5rem 0.75rem; font-size: 0.8125rem; }
</style>

<script>
(function () {
    var container = document.getElementById('itens-container');
    var btnAdicionar = document.getElementById('btn-adicionar-item');

    function atualizarBotoesRemover() {
        var rows = container.querySelectorAll('.item-row');
        rows.forEach(function (row) {
            var btn = row.querySelector('.btn-remover-item');
            btn.style.visibility = rows.length > 1 ? 'visible' : 'hidden';
        });
        var firstSelect = container.querySelector('.item-row select[name="id_prato[]"]');
        if (firstSelect) {
            firstSelect.required = true;
        }
    }

    btnAdicionar.addEventListener('click', function () {
        var ultima = container.querySelector('.item-row:last-child');
        var nova = ultima.cloneNode(true);
        nova.querySelector('select').value = '';
        nova.querySelector('select').required = false;
        nova.querySelector('input[type="number"]').value = '1';
        container.appendChild(nova);
        atualizarBotoesRemover();
    });

    container.addEventListener('click', function (e) {
        if (!e.target.classList.contains('btn-remover-item')) return;
        var rows = container.querySelectorAll('.item-row');
        if (rows.length <= 1) return;
        e.target.closest('.item-row').remove();
        atualizarBotoesRemover();
    });

    atualizarBotoesRemover();
})();
</script>

<?php include 'includes/footer.php'; ?>
