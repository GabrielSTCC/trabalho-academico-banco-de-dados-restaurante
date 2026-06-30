<?php
include('conexao.php');
require_once 'includes/mesa_sync.php';

sincronizarTodasMesas($conexao);

$pageTitle = 'Listar Mesas';
$currentPage = 'listar_mesas';
$pageDescription = 'Visualize o status das mesas e pedidos abertos';

$sql = "SELECT m.id_mesa, m.numero, m.capacidade, m.status AS status_mesa,
               p.id_pedido, c.nome AS cliente, p.status AS status_pedido,
               COUNT(ip.id_item) AS total_itens,
               COALESCE(SUM(ip.subtotal), 0) AS valor_total
        FROM Mesa m
        LEFT JOIN Pedido p ON p.id_mesa = m.id_mesa AND p.status = 'aberto'
        LEFT JOIN Cliente c ON c.id_cliente = p.id_cliente
        LEFT JOIN Item_Pedido ip ON ip.id_pedido = p.id_pedido
        GROUP BY m.id_mesa, m.numero, m.capacidade, m.status,
                 p.id_pedido, c.nome, p.status
        ORDER BY m.numero";

$resultado = $conexao->query($sql);

$badgeMesa = [
    'livre'     => 'badge-success',
    'ocupada'   => 'badge-warning',
    'reservada' => 'badge-info',
];

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Mesas</h2>
    <p>Status de cada mesa e pedido aberto vinculado</p>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Mesa</th>
                    <th>Status</th>
                    <th>Capacidade</th>
                    <th>Cliente</th>
                    <th>Itens</th>
                    <th>Valor</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($linha = $resultado->fetch_assoc()): ?>
                <?php $statusExibido = statusExibidoMesa($linha['status_mesa'], (bool) $linha['id_pedido']); ?>
                <tr>
                    <td><strong>#<?= $linha['numero'] ?></strong></td>
                    <td>
                        <span class="badge <?= $badgeMesa[$statusExibido] ?? 'badge-neutral' ?>">
                            <?= ucfirst($statusExibido) ?>
                        </span>
                    </td>
                    <td><?= $linha['capacidade'] ?> lugares</td>
                    <td>
                        <?php if ($linha['cliente']): ?>
                            <strong><?= htmlspecialchars($linha['cliente']) ?></strong>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($linha['id_pedido']): ?>
                            <span class="badge badge-neutral"><?= $linha['total_itens'] ?></span>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($linha['valor_total'] > 0): ?>
                            <strong>R$ <?= number_format($linha['valor_total'], 2, ',', '.') ?></strong>
                        <?php else: ?>
                            —
                        <?php endif; ?>
                    </td>
                    <td class="acoes-cell">
                        <a href="ver_mesa.php?id_mesa=<?= $linha['id_mesa'] ?>" class="btn btn-secondary btn-sm">Ver mesa</a>
                        <?php if ($linha['id_pedido']): ?>
                            <a href="editar_pedido.php?id_pedido=<?= $linha['id_pedido'] ?>" class="btn btn-primary btn-sm">Editar pedido</a>
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
.acoes-cell { display: flex; gap: 0.5rem; flex-wrap: wrap; }
</style>

<?php include 'includes/footer.php'; ?>
