<?php
include('conexao.php');

$pageTitle = 'Relatórios SQL';
$currentPage = 'relatorios';
$pageDescription = 'Consultas com WHERE, LIKE, COUNT, MIN, MAX e JOIN';

function badgeStatus($status) {
    $map = [
        'aberto'    => 'badge-warning',
        'fechado'   => 'badge-success',
        'cancelado' => 'badge-error',
    ];
    $class = $map[$status] ?? 'badge-neutral';
    return '<span class="badge ' . $class . '">' . htmlspecialchars($status) . '</span>';
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Relatórios SQL</h2>
    <p>Três consultas demonstrando WHERE, agregações e JOIN</p>
</div>

<!-- Relatório 1 -->
<div class="card report-section">
    <div class="card-header">
        <span class="card-title">1. Clientes com nome começando com "M"</span>
    </div>
    <div class="report-meta">
        <span class="badge badge-sql">WHERE</span>
        <span class="badge badge-sql">LIKE</span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql1 = "SELECT id_cliente, nome, telefone, email
                     FROM Cliente
                     WHERE nome LIKE 'M%'
                     ORDER BY nome";
            $resultado1 = $conexao->query($sql1);

            while ($linha = $resultado1->fetch_assoc()):
            ?>
                <tr>
                    <td><?= $linha['id_cliente'] ?></td>
                    <td><strong><?= htmlspecialchars($linha['nome']) ?></strong></td>
                    <td><?= htmlspecialchars($linha['telefone']) ?></td>
                    <td><?= htmlspecialchars($linha['email']) ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Relatório 2 -->
<div class="card report-section">
    <div class="card-header">
        <span class="card-title">2. Estatísticas de preço por categoria</span>
    </div>
    <div class="report-meta">
        <span class="badge badge-sql">COUNT</span>
        <span class="badge badge-sql">MIN</span>
        <span class="badge badge-sql">MAX</span>
        <span class="badge badge-sql">GROUP BY</span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Total de pratos</th>
                    <th>Preço mínimo</th>
                    <th>Preço máximo</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql2 = "SELECT categoria,
                            COUNT(*)   AS total_pratos,
                            MIN(preco) AS preco_minimo,
                            MAX(preco) AS preco_maximo
                     FROM Prato
                     WHERE preco > 10
                     GROUP BY categoria
                     ORDER BY preco_maximo DESC";
            $resultado2 = $conexao->query($sql2);

            while ($linha = $resultado2->fetch_assoc()):
            ?>
                <tr>
                    <td><strong><?= htmlspecialchars($linha['categoria']) ?></strong></td>
                    <td><span class="badge badge-neutral"><?= $linha['total_pratos'] ?></span></td>
                    <td>R$ <?= number_format($linha['preco_minimo'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($linha['preco_maximo'], 2, ',', '.') ?></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Relatório 3 -->
<div class="card report-section">
    <div class="card-header">
        <span class="card-title">3. Pedidos com cliente, mesa e valor total</span>
    </div>
    <div class="report-meta">
        <span class="badge badge-sql">INNER JOIN</span>
        <span class="badge badge-sql">SUM</span>
        <span class="badge badge-sql">GROUP BY</span>
    </div>
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Data</th>
                    <th>Cliente</th>
                    <th>Mesa</th>
                    <th>Status</th>
                    <th>Valor total</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $sql3 = "SELECT p.id_pedido,
                            p.data_pedido,
                            c.nome           AS cliente,
                            m.numero         AS mesa,
                            p.status,
                            SUM(ip.subtotal) AS valor_total
                     FROM Pedido p
                     INNER JOIN Cliente c      ON c.id_cliente = p.id_cliente
                     INNER JOIN Mesa m         ON m.id_mesa    = p.id_mesa
                     INNER JOIN Item_Pedido ip ON ip.id_pedido = p.id_pedido
                     GROUP BY p.id_pedido, p.data_pedido, c.nome, m.numero, p.status
                     ORDER BY p.data_pedido DESC";
            $resultado3 = $conexao->query($sql3);

            while ($linha = $resultado3->fetch_assoc()):
            ?>
                <tr>
                    <td>#<?= $linha['id_pedido'] ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($linha['data_pedido'])) ?></td>
                    <td><strong><?= htmlspecialchars($linha['cliente']) ?></strong></td>
                    <td>Mesa <?= $linha['mesa'] ?></td>
                    <td><?= badgeStatus($linha['status']) ?></td>
                    <td><strong>R$ <?= number_format($linha['valor_total'], 2, ',', '.') ?></strong></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
