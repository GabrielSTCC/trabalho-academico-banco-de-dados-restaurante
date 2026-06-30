<?php
include('conexao.php');
require_once 'includes/mesa_sync.php';

$pageTitle = 'Editar Pedido';
$currentPage = 'listar_pedidos';
$pageDescription = 'Alterar status e adicionar itens ao pedido';
$sucesso = '';
$erro = '';

$id_pedido = isset($_GET['id_pedido']) ? (int) $_GET['id_pedido'] : 0;
if (isset($_POST['id_pedido'])) {
    $id_pedido = (int) $_POST['id_pedido'];
}

$pedido = null;
$itensExistentes = [];

$pratos = [];
$resultadoPratos = $conexao->query("SELECT id_prato, nome, categoria, preco FROM Prato ORDER BY categoria, nome");
while ($linha = $resultadoPratos->fetch_assoc()) {
    $pratos[] = $linha;
}

function carregarPedido($conexao, $id_pedido) {
    $sql = "SELECT p.id_pedido, p.data_pedido, p.status, p.id_mesa,
                   c.nome AS cliente, m.numero AS mesa
            FROM Pedido p
            INNER JOIN Cliente c ON c.id_cliente = p.id_cliente
            LEFT JOIN Mesa m ON m.id_mesa = p.id_mesa
            WHERE p.id_pedido = $id_pedido";
    $resultado = $conexao->query($sql);
    return ($resultado && $resultado->num_rows > 0) ? $resultado->fetch_assoc() : null;
}

if ($id_pedido > 0 && isset($_POST['acao'])) {

    $pedidoAtual = carregarPedido($conexao, $id_pedido);

    if (!$pedidoAtual) {
        $erro = 'Pedido não encontrado.';
    } elseif ($_POST['acao'] === 'status') {

        $status = $_POST['status'] ?? 'aberto';
        $statusValidos = ['aberto', 'fechado', 'cancelado'];
        if (!in_array($status, $statusValidos, true)) {
            $status = 'aberto';
        }

        $sql = "UPDATE Pedido SET status = '$status' WHERE id_pedido = $id_pedido";

        if ($conexao->query($sql)) {
            sincronizarStatusMesa($conexao, (int) $pedidoAtual['id_mesa'], $status);
            $sucesso = 'Status do pedido atualizado com sucesso!';
        } else {
            $erro = 'Erro ao atualizar status: ' . $conexao->error;
        }

    } elseif ($_POST['acao'] === 'itens') {

        if ($pedidoAtual['status'] !== 'aberto') {
            $erro = 'Só é possível adicionar itens em pedidos abertos.';
        } else {
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

            if (empty($itensValidos)) {
                $erro = 'Adicione pelo menos um item válido.';
            } else {
                $conexao->begin_transaction();
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
                        $erro = 'Erro ao adicionar itens: ' . $conexao->error;
                        $falhou = true;
                        break;
                    }
                }

                if (!$falhou) {
                    $conexao->commit();
                    $sucesso = count($itensValidos) . ' ' . (count($itensValidos) === 1 ? 'item adicionado' : 'itens adicionados') . ' com sucesso!';
                }
            }
        }
    }
}

if ($id_pedido > 0) {
    $pedido = carregarPedido($conexao, $id_pedido);

    if ($pedido) {
        $sqlItens = "SELECT ip.id_item, pr.nome AS prato, pr.categoria,
                            ip.quantidade, ip.subtotal
                     FROM Item_Pedido ip
                     INNER JOIN Prato pr ON pr.id_prato = ip.id_prato
                     WHERE ip.id_pedido = $id_pedido
                     ORDER BY ip.id_item";
        $resItens = $conexao->query($sqlItens);
        while ($linha = $resItens->fetch_assoc()) {
            $itensExistentes[] = $linha;
        }
    }
}

$itensFormulario = [['id_prato' => '', 'quantidade' => 1]];

$badgePedido = [
    'aberto'    => 'badge-info',
    'fechado'   => 'badge-success',
    'cancelado' => 'badge-error',
];

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Editar Pedido #<?= $id_pedido ?></h2>
    <p>Altere o status ou adicione novos itens ao pedido</p>
    <div class="page-actions">
        <a href="listar_pedidos.php" class="btn btn-secondary">← Voltar</a>
    </div>
</div>

<?php if ($sucesso): ?>
    <div class="alert alert-success"><?= htmlspecialchars($sucesso) ?></div>
<?php endif; ?>

<?php if ($erro): ?>
    <div class="alert alert-error"><?= htmlspecialchars($erro) ?></div>
<?php endif; ?>

<?php if (!$pedido): ?>
    <div class="alert alert-error">Pedido não encontrado. Verifique o ID informado.</div>
<?php else: ?>

<div class="card" style="margin-bottom: 1.25rem;">
    <div class="table-wrapper">
        <table class="table">
            <tbody>
                <tr>
                    <th style="width: 120px;">Cliente</th>
                    <td><strong><?= htmlspecialchars($pedido['cliente']) ?></strong></td>
                </tr>
                <tr>
                    <th>Data</th>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                </tr>
                <tr>
                    <th>Mesa</th>
                    <td><?= $pedido['mesa'] !== null ? 'Mesa #' . $pedido['mesa'] : '—' ?></td>
                </tr>
                <tr>
                    <th>Status atual</th>
                    <td>
                        <span class="badge <?= $badgePedido[$pedido['status']] ?? 'badge-neutral' ?>">
                            <?= ucfirst($pedido['status']) ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header">
        <span class="card-title">Itens atuais</span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Prato</th>
                    <th>Categoria</th>
                    <th>Qtd</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($itensExistentes)): ?>
                <tr><td colspan="4">Nenhum item cadastrado.</td></tr>
            <?php else: ?>
                <?php foreach ($itensExistentes as $item): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($item['prato']) ?></strong></td>
                    <td><?= htmlspecialchars($item['categoria']) ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="card card-narrow" style="margin-bottom: 1.25rem;">
    <div class="card-header">
        <span class="card-title">Alterar status</span>
    </div>
    <form method="POST" style="padding: 0 1rem 1rem;">
        <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">
        <input type="hidden" name="acao" value="status">
        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" class="form-control">
                <?php foreach (['aberto', 'fechado', 'cancelado'] as $opcao): ?>
                    <option value="<?= $opcao ?>" <?= $pedido['status'] === $opcao ? 'selected' : '' ?>>
                        <?= ucfirst($opcao) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Atualizar status</button>
        </div>
    </form>
</div>

<?php if ($pedido['status'] === 'aberto'): ?>
<div class="card card-narrow">
    <div class="card-header">
        <span class="card-title">Adicionar itens</span>
    </div>
    <form method="POST" id="form-adicionar-itens" style="padding: 0 1rem 1rem;">
        <input type="hidden" name="id_pedido" value="<?= $id_pedido ?>">
        <input type="hidden" name="acao" value="itens">

        <div class="form-section" style="border-top: none; margin-top: 0; padding-top: 0;">
            <div class="form-section-header">
                <label>Novos pratos</label>
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
                                <option value="<?= $prato['id_prato'] ?>">
                                    <?= htmlspecialchars($prato['nome']) ?> — R$ <?= number_format($prato['preco'], 2, ',', '.') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group item-quantidade">
                        <label>Qtd</label>
                        <input type="number" name="quantidade[]" class="form-control" min="1" value="1">
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
            <button type="submit" class="btn btn-primary">Adicionar itens</button>
        </div>
    </form>
</div>
<?php else: ?>
<div class="alert alert-info">Este pedido está <?= $pedido['status'] ?>. Não é possível adicionar novos itens.</div>
<?php endif; ?>

<style>
.form-section { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--color-border); }
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
    if (!container || !btnAdicionar) return;

    function atualizarBotoesRemover() {
        var rows = container.querySelectorAll('.item-row');
        rows.forEach(function (row) {
            var btn = row.querySelector('.btn-remover-item');
            btn.style.visibility = rows.length > 1 ? 'visible' : 'hidden';
        });
        var firstSelect = container.querySelector('.item-row select[name="id_prato[]"]');
        if (firstSelect) firstSelect.required = true;
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

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
