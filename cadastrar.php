<?php
include('conexao.php');

$pageTitle = 'Cadastrar Cliente';
$currentPage = 'cadastrar';
$pageDescription = 'Adicionar um novo cliente ao banco de dados';
$sucesso = false;

if (isset($_POST['nome'])) {

    $nome = $_POST['nome'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];

    $sql = "INSERT INTO Cliente(nome, telefone, email)
            VALUES('$nome', '$telefone', '$email')";

    if ($conexao->query($sql)) {
        $sucesso = true;
    }
}

include 'includes/header.php';
?>

<div class="page-header">
    <h2>Cadastrar Cliente</h2>
    <p>Preencha os dados do novo cliente</p>
</div>

<?php if ($sucesso): ?>
    <div class="alert alert-success">Cliente cadastrado com sucesso!</div>
<?php endif; ?>

<div class="card card-narrow">
    <form method="POST">
        <div class="form-group">
            <label for="nome">Nome</label>
            <input type="text" id="nome" name="nome" class="form-control" required
                   value="<?= isset($_POST['nome']) && !$sucesso ? htmlspecialchars($_POST['nome']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input type="text" id="telefone" name="telefone" class="form-control"
                   placeholder="(11) 99999-9999"
                   value="<?= isset($_POST['telefone']) && !$sucesso ? htmlspecialchars($_POST['telefone']) : '' ?>">
        </div>

        <div class="form-group">
            <label for="email">E-mail</label>
            <input type="email" id="email" name="email" class="form-control"
                   placeholder="cliente@email.com"
                   value="<?= isset($_POST['email']) && !$sucesso ? htmlspecialchars($_POST['email']) : '' ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Cadastrar</button>
            <a href="listar.php" class="btn btn-secondary">Ver listagem</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
