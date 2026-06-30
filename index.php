<?php
$pageTitle = 'Dashboard';
$currentPage = 'index';
$pageDescription = 'Painel de controle do sistema de gestão do restaurante';
include 'includes/header.php';
?>

<div class="hero">
    <h2>Sistema Restaurante</h2>
    <p>Gestão de clientes, pedidos e relatórios SQL. Selecione uma opção abaixo para começar.</p>
</div>

<div class="page-header">
    <h2>Atalhos</h2>
    <p>Acesso rápido às funcionalidades do sistema</p>
</div>

<div class="nav-grid">
    <a href="cadastrar.php" class="nav-card">
        <div class="nav-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
                <circle cx="9" cy="7" r="4"/>
                <line x1="19" y1="8" x2="19" y2="14"/><line x1="22" y1="11" x2="16" y2="11"/>
            </svg>
        </div>
        <h3>Cadastrar Cliente</h3>
        <p>Adicionar novo cliente ao banco de dados</p>
    </a>

    <a href="listar.php" class="nav-card">
        <div class="nav-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
            </svg>
        </div>
        <h3>Listar Clientes</h3>
        <p>Visualizar todos os clientes cadastrados</p>
    </a>

    <a href="editar.php" class="nav-card">
        <div class="nav-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
            </svg>
        </div>
        <h3>Editar Cliente</h3>
        <p>Atualizar dados de um cliente existente</p>
    </a>

    <a href="excluir.php" class="nav-card">
        <div class="nav-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="3 6 5 6 21 6"/>
                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
            </svg>
        </div>
        <h3>Excluir Cliente</h3>
        <p>Remover cliente sem pedidos vinculados</p>
    </a>

    <a href="cadastrar_pedido.php" class="nav-card">
        <div class="nav-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="12" y1="10" x2="12" y2="14"/>
                <line x1="9" y1="12" x2="15" y2="12"/>
            </svg>
        </div>
        <h3>Cadastrar Pedido</h3>
        <p>Registrar pedido com cliente, mesa e pratos</p>
    </a>

    <a href="listar_pedidos.php" class="nav-card">
        <div class="nav-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/>
                <line x1="8" y1="18" x2="21" y2="18"/>
            </svg>
        </div>
        <h3>Listar Pedidos</h3>
        <p>Visualizar pedidos e clientes vinculados</p>
    </a>

    <a href="relatorios.php" class="nav-card">
        <div class="nav-card-icon">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="20" x2="18" y2="10"/>
                <line x1="12" y1="20" x2="12" y2="4"/>
                <line x1="6" y1="20" x2="6" y2="14"/>
            </svg>
        </div>
        <h3>Relatórios SQL</h3>
        <p>Consultas com WHERE, JOIN, COUNT, MIN e MAX</p>
    </a>
</div>

<?php include 'includes/footer.php'; ?>
