<?php
include('conexao.php');
require_once 'includes/mesa_sync.php';

sincronizarTodasMesas($conexao);

$pageTitle = 'Detalhe da Mesa';
$currentPage = 'listar_mesas';
$pageDescription = 'Status da mesa e itens do pedido aberto';

$id_mesa = isset($_GET['id_mesa']) ? (int) $_GET['id_mesa'] : 0;
$mesa = null;
$pedido = null;
$itens = null;
$valorTotal = 0;

$badgeMesa = [
    'livre'     => 'badge-success',
    'ocupada'   => 'badge-warning',
    'reservada' => 'badge-info',
];

$badgePedido = [
    'aberto'    => 'badge-info',
    'fechado'   => 'badge-success',
    'cancelado' => 'badge-error',
];

if ($id_mesa > 0) {
    $sqlMesa = "SELECT id_mesa, numero, capacidade, status
                FROM Mesa WHERE id_mesa = $id_mesa";
    $resultadoMesa = $conexao->query($sqlMesa);

    if ($resultadoMesa && $resultadoMesa->num_rows > 0) {
        $mesa = $resultadoMesa->fetch_assoc();

        $sqlPedido = "SELECT p.id_pedido, p.data_pedido, p.status, c.nome AS cliente
                      FROM Pedido p
                      INNER JOIN Cliente c ON c.id_cliente = p.id_cliente
                      WHERE p.id_mesa = $id_mesa AND p.status = 'aberto'
                      LIMIT 1";

        $resultadoPedido = $conexao->query($sqlPedido);

        if ($resultadoPedido && $resultadoPedido->num_rows > 0) {
            $pedido = $resultadoPedido->fetch_assoc();
            $id_pedido = $pedido['id_pedido'];

            $sqlItens = "SELECT ip.id_item, pr.nome AS prato, pr.categoria,
                                ip.quantidade, ip.subtotal
                         FROM Item_Pedido ip
                         INNER JOIN Prato pr ON pr.id_prato = ip.id_prato
                         WHERE ip.id_pedido = $id_pedido
                         ORDER BY ip.id_item";

            $itens = $conexao->query($sqlItens);
        }
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Mesa #<?= $mesa ? $mesa['numero'] : $id_mesa ?></h2>
    <p>Detalhamento da mesa e pedido em andamento</p>
    <div class="page-actions">
        <a href="listar_mesas.php" class="btn btn-secondary">← Voltar</a>
        <?php if ($pedido): ?>
            <a href="editar_pedido.php?id_pedido=<?= $pedido['id_pedido'] ?>" class="btn btn-primary">Editar pedido</a>
        <?php endif; ?>
    </div>
</div>

<?php if (!$mesa): ?>
    <div class="alert alert-error">Mesa não encontrada. Verifique o ID informado.</div>
<?php else: ?>

<div class="card" style="margin-bottom: 1.25rem;">
    <div class="table-wrapper">
        <table class="table">
            <tbody>
                <tr>
                    <th style="width: 140px;">Número</th>
                    <td><strong>#<?= $mesa['numero'] ?></strong></td>
                </tr>
                <tr>
                    <th>Capacidade</th>
                    <td><?= $mesa['capacidade'] ?> lugares</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        <?php $statusExibido = statusExibidoMesa($mesa['status'], (bool) $pedido); ?>
                        <span class="badge <?= $badgeMesa[$statusExibido] ?? 'badge-neutral' ?>">
                            <?= ucfirst($statusExibido) ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php if (!$pedido): ?>
    <div class="alert alert-info">
        Nenhum pedido aberto nesta mesa no momento.
        <a href="cadastrar_pedido.php">Cadastrar pedido</a>
    </div>
<?php else: ?>

<div class="card" style="margin-bottom: 1.25rem;">
    <div class="card-header">
        <span class="card-title">Pedido #<?= $pedido['id_pedido'] ?></span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <tbody>
                <tr>
                    <th style="width: 140px;">Cliente</th>
                    <td><strong><?= htmlspecialchars($pedido['cliente']) ?></strong></td>
                </tr>
                <tr>
                    <th>Data</th>
                    <td><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                </tr>
                <tr>
                    <th>Status</th>
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

<div class="card">
    <div class="card-header">
        <span class="card-title">Itens do pedido</span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Prato</th>
                    <th>Categoria</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($itens && $itens->num_rows > 0): ?>
                <?php while ($linha = $itens->fetch_assoc()): ?>
                    <?php $valorTotal += $linha['subtotal']; ?>
                    <tr>
                        <td><?= $linha['id_item'] ?></td>
                        <td><strong><?= htmlspecialchars($linha['prato']) ?></strong></td>
                        <td><?= htmlspecialchars($linha['categoria']) ?></td>
                        <td><?= $linha['quantidade'] ?></td>
                        <td>R$ <?= number_format($linha['subtotal'], 2, ',', '.') ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Nenhum item cadastrado neste pedido.</td>
                </tr>
            <?php endif; ?>
            </tbody>
            <?php if ($valorTotal > 0): ?>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: 600;">Total</td>
                    <td><strong>R$ <?= number_format($valorTotal, 2, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>

<?php endif; ?>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
