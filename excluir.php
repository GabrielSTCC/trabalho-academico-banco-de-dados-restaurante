<?php
include('conexao.php');

$pageTitle = 'Excluir Cliente';
$currentPage = 'excluir';
$pageDescription = 'Remover um cliente do banco de dados';
$mensagem = null;
$tipoMensagem = null;

if (isset($_POST['id'])) {

    $id = (int) $_POST['id'];

    $sql = "DELETE FROM Cliente WHERE id_cliente = $id";

    if ($conexao->query($sql)) {
        $mensagem = 'Cliente excluído com sucesso!';
        $tipoMensagem = 'success';
    } elseif ($conexao->errno === 1451) {
        $mensagem = 'Não foi possível excluir: cliente possui pedidos vinculados.';
        $tipoMensagem = 'error';
    } else {
        $mensagem = 'Erro ao excluir: ' . $conexao->error;
        $tipoMensagem = 'error';
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Excluir Cliente</h2>
    <p>Remova um cliente que não possua pedidos vinculados</p>
</div>

<?php if ($mensagem): ?>
    <div class="alert alert-<?= $tipoMensagem ?>"><?= htmlspecialchars($mensagem) ?></div>
<?php endif; ?>

<div class="alert alert-info">
    Apenas clientes <strong>sem pedidos</strong> podem ser excluídos.
    Consulte a <a href="listar.php">listagem</a> para verificar quais estão disponíveis (ex.: ID 4).
</div>

<div class="card card-narrow">
    <form method="POST">
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

<?php include 'includes/footer.php'; ?>
