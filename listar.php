<?php
include('conexao.php');

$pageTitle = 'Listar Clientes';
$currentPage = 'listar';
$pageDescription = 'Visualize todos os clientes cadastrados no sistema';

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

<?php if (isset($_GET['msg']) && $_GET['msg'] === 'excluido'): ?>
    <div class="alert alert-success">Cliente excluído com sucesso!</div>
<?php endif; ?>

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
                    <th class="th-acoes">Ações</th>
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
                    <td class="td-acoes">
                        <form method="POST" action="excluir.php" class="form-excluir-inline"
                              onsubmit="return confirmarExclusao(<?= (int) $linha['total_pedidos'] ?>)">
                            <input type="hidden" name="id" value="<?= (int) $linha['id_cliente'] ?>">
                            <input type="hidden" name="redirect" value="listar.php">
                            <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.th-acoes { text-align: right; width: 1%; white-space: nowrap; }
.td-acoes { text-align: right; }
.form-excluir-inline { display: inline; margin: 0; }
.btn-sm { padding: 0.375rem 0.625rem; font-size: 0.8125rem; }
</style>

<script>
function confirmarExclusao(totalPedidos) {
    if (totalPedidos > 0) {
        return confirm('Ao excluir perderá os dados de compra do cliente. Deseja continuar?');
    }
    return confirm('Deseja excluir este cliente?');
}
</script>

<?php include 'includes/footer.php'; ?>
