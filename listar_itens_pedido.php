<?php
include('conexao.php');

$pageTitle = 'Itens do Pedido';
$currentPage = 'listar_pedidos';
$pageDescription = 'Detalhamento dos itens de um pedido';

$id_pedido = isset($_GET['id_pedido']) ? (int) $_GET['id_pedido'] : 0;
$pedido = null;
$itens = null;
$valorTotal = 0;

$badgeStatus = [
    'aberto'    => 'badge-info',
    'fechado'   => 'badge-success',
    'cancelado' => 'badge-error',
];

if ($id_pedido > 0) {
    $sqlPedido = "SELECT p.id_pedido, p.data_pedido, p.status,
                         c.nome AS cliente, m.numero AS mesa
                  FROM Pedido p
                  INNER JOIN Cliente c ON c.id_cliente = p.id_cliente
                  LEFT JOIN Mesa m ON m.id_mesa = p.id_mesa
                  WHERE p.id_pedido = $id_pedido";

    $resultadoPedido = $conexao->query($sqlPedido);

    if ($resultadoPedido && $resultadoPedido->num_rows > 0) {
        $pedido = $resultadoPedido->fetch_assoc();

        $sqlItens = "SELECT ip.id_item, pr.nome AS prato, pr.categoria,
                            ip.quantidade, ip.subtotal
                     FROM Item_Pedido ip
                     INNER JOIN Prato pr ON pr.id_prato = ip.id_prato
                     WHERE ip.id_pedido = $id_pedido
                     ORDER BY ip.id_item";

        $itens = $conexao->query($sqlItens);
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Itens do Pedido #<?= $id_pedido ?></h2>
    <p>Detalhamento dos pratos incluídos no pedido</p>
    <div class="page-actions">
        <a href="listar_pedidos.php" class="btn btn-secondary">← Voltar</a>
    </div>
</div>

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
                    <th>Status</th>
                    <td>
                        <span class="badge <?= $badgeStatus[$pedido['status']] ?? 'badge-neutral' ?>">
                            <?= ucfirst($pedido['status']) ?>
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
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
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right; font-weight: 600;">Total</td>
                    <td><strong>R$ <?= number_format($valorTotal, 2, ',', '.') ?></strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?php endif; ?>

<?php include 'includes/footer.php'; ?>
