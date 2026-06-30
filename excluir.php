<?php
include('conexao.php');

$pageTitle = 'Excluir Cliente';
$currentPage = 'excluir';
$pageDescription = 'Remover um cliente do banco de dados';
$mensagem = null;
$tipoMensagem = null;

$redirectsPermitidos = ['listar.php'];

function excluirClienteComCascata(mysqli $conexao, int $id): bool
{
    $conexao->begin_transaction();

    $sqlItens = "DELETE ip FROM Item_Pedido ip
                 INNER JOIN Pedido p ON p.id_pedido = ip.id_pedido
                 WHERE p.id_cliente = $id";
    if (!$conexao->query($sqlItens)) {
        $conexao->rollback();
        return false;
    }

    $sqlPedidos = "DELETE FROM Pedido WHERE id_cliente = $id";
    if (!$conexao->query($sqlPedidos)) {
        $conexao->rollback();
        return false;
    }

    $sqlCliente = "DELETE FROM Cliente WHERE id_cliente = $id";
    if (!$conexao->query($sqlCliente)) {
        $conexao->rollback();
        return false;
    }

    $conexao->commit();
    return true;
}

if (isset($_POST['id'])) {

    $id = (int) $_POST['id'];
    $redirect = isset($_POST['redirect']) ? basename($_POST['redirect']) : '';

    if (excluirClienteComCascata($conexao, $id)) {
        if ($redirect !== '' && in_array($redirect, $redirectsPermitidos, true)) {
            header('Location: ' . $redirect . '?msg=excluido');
            exit;
        }
        $mensagem = 'Cliente excluído com sucesso!';
        $tipoMensagem = 'success';
    } else {
        $mensagem = 'Erro ao excluir: ' . $conexao->error;
        $tipoMensagem = 'error';
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Excluir Cliente</h2>
    <p>Remova um cliente pelo ID. Se houver pedidos, o histórico de compras também será removido.</p>
</div>

<?php if ($mensagem): ?>
    <div class="alert alert-<?= $tipoMensagem ?>"><?= htmlspecialchars($mensagem) ?></div>
<?php endif; ?>

<div class="alert alert-info">
    Clientes com pedidos podem ser excluídos, mas <strong>perderão os dados de compra</strong> vinculados.
    Você também pode excluir diretamente na <a href="listar.php">listagem</a>.
</div>

<div class="card card-narrow">
    <form method="POST" id="form-excluir-standalone" onsubmit="return confirmarExclusaoStandalone()">
        <div class="form-group">
            <label for="id">ID do cliente</label>
            <input type="number" id="id" name="id" class="form-control" required
                   value="<?= isset($_POST['id']) ? (int) $_POST['id'] : '' ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-danger">Excluir cliente</button>
            <a href="listar.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script>
function confirmarExclusaoStandalone() {
    return confirm('Ao excluir perderá os dados de compra do cliente. Deseja continuar?');
}
</script>

<?php include 'includes/footer.php'; ?>
