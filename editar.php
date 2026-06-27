<?php
include('conexao.php');

$pageTitle = 'Editar Cliente';
$currentPage = 'editar';
$pageDescription = 'Atualizar dados de um cliente existente';
$sucesso = false;

if (isset($_POST['id'])) {

    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql = "UPDATE Cliente
            SET nome='$nome',
                telefone='$telefone',
                email='$email'
            WHERE id_cliente=$id";

    if ($conexao->query($sql)) {
        $sucesso = true;
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Editar Cliente</h2>
    <p>Informe o ID e os novos dados do cliente</p>
</div>

<?php if ($sucesso): ?>
    <div class="alert alert-success">Cliente atualizado com sucesso!</div>
<?php endif; ?>

<div class="card card-narrow">
    <form method="POST">
        <div class="form-group">
            <label for="id">ID do cliente</label>
            <input type="number" id="id" name="id" class="form-control" required
                   value="<?= isset($_POST['id']) ? (int) $_POST['id'] : '' ?>">
        </div>

        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" required
                   value="<?= isset($_POST['nome']) ? htmlspecialchars($_POST['nome']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" class="form-control"
                   value="<?= isset($_POST['telefone']) ? htmlspecialchars($_POST['telefone']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" class="form-control"
                   value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Atualizar</button>
            <a href="listar.php" class="btn btn-secondary">Ver listagem</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
