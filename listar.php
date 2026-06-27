<?php
include('conexao.php');

$pageTitle = 'Listar Clientes';
$currentPage = 'listar';
$pageDescription = 'Visualize todos os clientes e verifique quais podem ser excluídos';

$sql = "SELECT c.id_cliente, c.nome, c.telefone, c.email,
               COUNT(p.id_pedido) AS total_pedidos
        FROM Cliente c
        LEFT JOIN Pedido p ON p.id_cliente = c.id_cliente
        GROUP BY c.id_cliente, c.nome, c.telefone, c.email
        ORDER BY c.nome";

$resultado = $conexao->query($sql);

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Clientes</h2>
    <p>Lista completa de clientes cadastrados no sistema</p>
    <div class="page-actions">
        <a href="cadastrar.php" class="btn btn-primary">+ Novo cliente</a>
    </div>
</div>

<div class="card">
    <div class="table-wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>E-mail</th>
                    <th>Pedidos</th>
                    <th>Pode excluir?</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($linha = $resultado->fetch_assoc()): ?>
                <tr>
                    <td><?= $linha['id_cliente'] ?></td>
                    <td><strong><?= htmlspecialchars($linha['nome']) ?></strong></td>
                    <td><?= htmlspecialchars($linha['telefone']) ?></td>
                    <td><?= htmlspecialchars($linha['email']) ?></td>
                    <td>
                        <span class="badge badge-neutral"><?= $linha['total_pedidos'] ?></span>
                    </td>
                    <td>
                        <?php if ($linha['total_pedidos'] == 0): ?>
                            <span class="badge badge-success">Sim</span>
                        <?php else: ?>
                            <span class="badge badge-error">Não (tem pedidos)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
