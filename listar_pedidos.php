<?php
include('conexao.php');

$pageTitle = 'Listar Pedidos';
$currentPage = 'listar_pedidos';
$pageDescription = 'Visualize todos os pedidos e seus clientes vinculados';

$sql = "SELECT p.id_pedido, p.data_pedido, c.nome AS cliente,
               m.numero AS mesa, p.status,
               COUNT(ip.id_item) AS total_itens,
               COALESCE(SUM(ip.subtotal), 0) AS valor_total
        FROM Pedido p
        INNER JOIN Cliente c ON c.id_cliente = p.id_cliente
        LEFT JOIN Mesa m         ON m.id_mesa    = p.id_mesa
        LEFT JOIN Item_Pedido ip ON ip.id_pedido = p.id_pedido
        GROUP BY p.id_pedido, p.data_pedido, c.nome, m.numero, p.status
        ORDER BY p.data_pedido DESC";

$resultado = $conexao->query($sql);

$badgeStatus = [
    'aberto'    => 'badge-info',
    'fechado'   => 'badge-success',
    'cancelado' => 'badge-error',
];

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Pedidos</h2>
    <p>Lista de pedidos vinculados aos clientes</p>
    <div class="page-actions">
        <a href="cadastrar_pedido.php" class="btn btn-primary">+ Novo pedido</a>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Mesa</th>
                    <th>Status</th>
                    <th>Itens</th>
                    <th>Valor total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($linha = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $linha['id_pedido'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($linha['data_pedido'])) ?></td>
                    <td><strong><?= htmlspecialchars($linha['cliente']) ?></strong></td>
                    <td>
                        <?php if ($linha['mesa'] !== null): ?>
                            <span class="badge badge-neutral">#<?= $linha['mesa'] ?></span>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <span class="badge <?= $badgeStatus[$linha['status']] ?? 'badge-neutral' ?>">
                            <?= ucfirst($linha['status']) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-neutral"><?= $linha['total_itens'] ?></span>
                    </td>
                    <td>
                        <?php if ($linha['valor_total'] > 0): ?>
                            <strong>R$ <?= number_format($linha['valor_total'], 2, ',', '.') ?></strong>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($linha['total_itens'] > 0): ?>
                            <a href="listar_itens_pedido.php?id_pedido=<?= $linha['id_pedido'] ?>" class="btn btn-secondary btn-sm">Ver itens</a>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.btn-sm { padding: 0.375rem 0.625rem; font-size: 0.8125rem; }
</style>

<?php include 'includes/footer.php'; ?>
