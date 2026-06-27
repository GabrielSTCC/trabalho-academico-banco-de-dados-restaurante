# Guia para a Equipe

Este documento orienta cada integrante sobre **o que fazer**, **onde está no projeto** e **como rodar localmente**.

Repositório: https://github.com/GabrielSTCC/trabalho-academico-banco-de-dados-restaurante

---

## Primeira vez — configurar o ambiente

### 1. Clonar o projeto

```powershell
git clone https://github.com/GabrielSTCC/trabalho-academico-banco-de-dados-restaurante.git
cd trabalho-academico-banco-de-dados-restaurante
```

### 2. Instalar dependências

| Software | Para quê |
|----------|----------|
| **MySQL 8.0** | Banco de dados |
| **PHP 8.x** | CRUD web (extensão `mysqli` ativa) |

No Windows, adicione ao PATH (se necessário):

- `C:\php`
- `C:\Program Files\MySQL\MySQL Server 8.0\bin`

### 3. Configurar credenciais (cada pessoa no seu PC)

```powershell
copy config.example.php config.php
```

Edite `config.php` com **sua** senha local do MySQL (`root`).

> **Nunca** commite `config.php` — ele está no `.gitignore`.

### 4. Criar banco e rodar o sistema

```powershell
.\setup.ps1      # cria banco restaurante + dados de teste
.\iniciar.ps1    # inicia servidor PHP
```

Acesse: **http://localhost:8000**

---

## Divisão sugerida de tarefas

Preencha os nomes e marque conforme for concluindo.

| Tópico | Pontos | Responsável | Status | O que fazer |
|--------|--------|-------------|--------|-------------|
| Diagrama conceitual | 1,5 | _[nome]_ | [ ] | Desenhar no brModelo, exportar PNG. Guia: `docs/diagramas/DIAGRAMA_CONCEITUAL.md` |
| Diagrama lógico | 1,5 | _[nome]_ | [ ] | Reverse Engineer no Workbench, exportar PNG. Guia: `docs/diagramas/DIAGRAMA_LOGICO.md` |
| SQL DDL + DML | 2,0 | _[nome]_ | [x] | Revisar `sql/01` a `03`, executar no Workbench, tirar prints |
| 3 relatórios SQL | 2,0 | _[nome]_ | [ ] | Executar `sql/04` e print de `relatorios.php` |
| CRUD web (PHP) | 3,0 | _[nome]_ | [x] | Testar cadastrar/listar/editar/excluir, tirar prints |
| Documento / entrega | — | _[nome]_ | [ ] | Montar PDF/zip seguindo `docs/ENTREGA.md` |
| Apresentação | — | _[nome]_ | [ ] | Demonstrar na ordem do roteiro em `ENTREGA.md` |

---

## Mapa do projeto — o que cada arquivo faz

### PHP (interface web)

| Arquivo | Função |
|---------|--------|
| `index.php` | Dashboard com atalhos |
| `cadastrar.php` | INSERT — cadastrar cliente |
| `listar.php` | SELECT — listar clientes |
| `editar.php` | UPDATE — editar cliente |
| `excluir.php` | DELETE — excluir cliente (bloqueia se tiver pedidos) |
| `relatorios.php` | Exibe os 3 relatórios SQL na web |
| `conexao.php` | Conexão com MySQL (usa `config.php`) |
| `includes/header.php` | Layout: sidebar e menu |
| `includes/footer.php` | Layout: rodapé |
| `assets/css/style.css` | Estilos visuais |

### SQL

| Arquivo | Função |
|---------|--------|
| `sql/01_ddl.sql` | CREATE DATABASE, CREATE TABLE, ALTER TABLE |
| `sql/02_dados_exemplo.sql` | INSERT — dados de teste |
| `sql/03_dml_exemplos.sql` | UPDATE, DELETE, SELECT de exemplo |
| `sql/04_relatorios.sql` | 3 relatórios (WHERE/LIKE, COUNT/MIN/MAX, JOIN) |

### Scripts Windows

| Arquivo | Função |
|---------|--------|
| `setup.ps1` | Cria/recria o banco (lê senha de `config.php`) |
| `iniciar.ps1` | Inicia servidor PHP em `localhost:8000` |

### Documentação

| Arquivo | Função |
|---------|--------|
| `docs/TRABALHO_BD.md` | Documento principal do trabalho |
| `docs/ENTREGA.md` | Checklist de prints e entrega |
| `docs/diagramas/` | Diagramas de referência + `visualizar.html` |

---

## Dicas para demonstração

### CRUD — o que testar

1. **Listar** — ver clientes; ID 4 (Pedro) pode ser excluído
2. **Cadastrar** — adicionar cliente novo
3. **Editar** — alterar telefone ou e-mail
4. **Excluir ID 4** — deve funcionar
5. **Excluir ID 1** — deve bloquear (cliente com pedidos)

### Relatórios

Abra `relatorios.php` ou execute `sql/04_relatorios.sql` no Workbench.

### Diagramas

- Referência rápida: abra `docs/diagramas/visualizar.html` no navegador
- Entrega oficial: exportar PNG do brModelo e do Workbench

---

## Comunicação na equipe

- **Dúvidas sobre SQL/modelo** → ver `docs/TRABALHO_BD.md`
- **Dúvidas sobre entrega/prints** → ver `docs/ENTREGA.md`
- **Problema ao rodar** → conferir se MySQL está rodando e se `config.php` existe
- **Alterações no código** → fazer commit descrevendo o que mudou; **nunca** incluir senhas

---

## Checklist individual (antes da entrega)

- [ ] Clonei o repo e rodei `setup.ps1` + `iniciar.ps1` com sucesso
- [ ] Sei qual tópico da nota sou responsável
- [ ] Tirei os prints da minha parte (ver `ENTREGA.md`)
- [ ] Enviei PNG/diagrama para quem monta o documento final
- [ ] Revisei que nenhum arquivo com senha foi commitado
