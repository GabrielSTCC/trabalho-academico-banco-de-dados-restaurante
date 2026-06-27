# Trabalho Acadêmico de Banco de Dados — Restaurante

Sistema de gestão de restaurante desenvolvido para a disciplina de Banco de Dados.

**Repositório:** https://github.com/GabrielSTCC/trabalho-academico-banco-de-dados-restaurante

---

## Para a equipe

Cada integrante deve começar por **[docs/EQUIPE.md](docs/EQUIPE.md)** — lá estão:

- Como clonar e configurar o ambiente
- Divisão sugerida de tarefas (diagramas, SQL, CRUD, entrega)
- Mapa de todos os arquivos do projeto
- Checklist individual antes da entrega

---

## Conteúdo do projeto

| Parte | Descrição |
|-------|-----------|
| `sql/` | DDL, DML e 3 relatórios SQL |
| `*.php` | CRUD web da tabela Cliente + relatórios |
| `docs/` | Documentação, diagramas e guias |
| `assets/` | Estilos CSS da interface |

---

## Requisitos

- MySQL 8.0
- PHP 8.x com extensão **mysqli**

---

## Configuração rápida

```powershell
git clone https://github.com/GabrielSTCC/trabalho-academico-banco-de-dados-restaurante.git
cd trabalho-academico-banco-de-dados-restaurante

copy config.example.php config.php
# Edite config.php com sua senha do MySQL

.\setup.ps1
.\iniciar.ps1
```

Acesse: **http://localhost:8000**

---

## Documentação

| Documento | Conteúdo |
|-----------|----------|
| [Guia da equipe](docs/EQUIPE.md) | Clone, tarefas, mapa de arquivos |
| [Trabalho completo](docs/TRABALHO_BD.md) | Requisitos, modelagem, SQL |
| [Guia de entrega](docs/ENTREGA.md) | Prints, zip, roteiro de apresentação |
| [Diagramas](docs/diagramas/) | Conceitual, lógico e visualização HTML |

---

## Estrutura resumida

```
├── index.php              # Dashboard
├── cadastrar.php          # Create
├── listar.php             # Read
├── editar.php             # Update
├── excluir.php            # Delete
├── relatorios.php         # 3 relatórios SQL
├── sql/                   # Scripts do banco
├── includes/              # Layout (header/footer)
├── assets/css/            # Estilos
└── docs/                  # Documentação
```
