# Trabalho de Banco de Dados — Sistema Restaurante



**Equipe:** _[preencher nomes dos integrantes — ver docs/EQUIPE.md]_  

**Repositório:** https://github.com/GabrielSTCC/trabalho-academico-banco-de-dados-restaurante  

**Tema:** Gestão de um restaurante (clientes, mesas, pratos e pedidos)



---



## Ambiente atual



| Componente | Versão / Caminho |

|------------|------------------|

| MySQL Server | 8.0 — serviço `MySQL80` |

| PHP | 8.5 — `C:\php` (no PATH) |

| Servidor web | PHP built-in via `iniciar.ps1` → `http://localhost:8000` |

| Banco de dados | `restaurante` (criado por `setup.ps1`) |



### Fluxo rápido



```powershell
git clone https://github.com/GabrielSTCC/trabalho-academico-banco-de-dados-restaurante.git
cd trabalho-academico-banco-de-dados-restaurante

copy config.example.php config.php
# Edite config.php com sua senha do MySQL

.\setup.ps1      # cria/recria banco e dados
.\iniciar.ps1    # inicia servidor PHP
```



Acesse: **http://localhost:8000**



---



## Resumo dos tópicos atendidos



| Tópico | Pontos | Onde está no projeto |

|--------|--------|----------------------|

| Diagrama conceitual | 1,5 | `docs/diagramas/DIAGRAMA_CONCEITUAL.md` + brModelo |

| Diagrama lógico | 1,5 | `docs/diagramas/DIAGRAMA_LOGICO.md` + MySQL Workbench |

| SQL DDL e DML | 2,0 | Pasta `sql/` |

| 3 relatórios SQL | 2,0 | `sql/04_relatorios.sql` e `relatorios.php` |

| CRUD web (PHP) | 3,0 | `cadastrar.php`, `listar.php`, `editar.php`, `excluir.php` |



Guia completo de entrega: [`docs/ENTREGA.md`](ENTREGA.md)

Guia para a equipe: [`docs/EQUIPE.md`](EQUIPE.md)



---



## 1. Diagrama Conceitual (brModelo)



Arquivo de referência: [`docs/diagramas/DIAGRAMA_CONCEITUAL.md`](diagramas/DIAGRAMA_CONCEITUAL.md)  

Visualização no navegador: [`docs/diagramas/visualizar.html`](diagramas/visualizar.html)



Ferramenta oficial: [brModelo](https://www.sis4.com/brModelo/) (online, gratuito).



### Entidades e atributos



| Entidade | Atributos | Identificador |

|----------|-----------|---------------|

| **CLIENTE** | nome, telefone, email, data_cadastro | id_cliente |

| **MESA** | numero, capacidade, status | id_mesa |

| **PRATO** | nome, categoria, preco | id_prato |

| **PEDIDO** | data_pedido, status | id_pedido |

| **ITEM_PEDIDO** | quantidade, subtotal | id_item |



### Relacionamentos



```

CLIENTE (1) ---- realiza ---- (N) PEDIDO

MESA    (1) ---- recebe ---- (N) PEDIDO

PEDIDO  (1) ---- contém ---- (N) ITEM_PEDIDO

PRATO   (1) ---- compõe ---- (N) ITEM_PEDIDO

```



### Passo a passo no brModelo



1. Acesse https://www.sis4.com/brModelo/ e crie um novo modelo.

2. Adicione as 5 entidades com os atributos listados acima.

3. Marque os identificadores (sublinhados no brModelo).

4. Conecte os relacionamentos com cardinalidade 1:N conforme o diagrama.

5. Exporte como imagem (PNG) para anexar ao trabalho.



---



## 2. Diagrama Lógico (MySQL Workbench)



Arquivo de referência: [`docs/diagramas/DIAGRAMA_LOGICO.md`](diagramas/DIAGRAMA_LOGICO.md)



### Gerar automaticamente a partir do SQL



1. Execute `.\setup.ps1` (ou scripts SQL manualmente).

2. No Workbench: **Database → Reverse Engineer…**

3. Selecione a conexão `localhost` e o banco `restaurante`.

4. Marque todas as tabelas e finalize o assistente.

5. Exporte: **File → Export → Export as PNG** (ou PDF).



### Tabelas e relacionamentos



```

Cliente (id_cliente PK)

    ↑

    | FK id_cliente

Pedido (id_pedido PK, id_cliente FK, id_mesa FK, data_pedido, status)

    ↑                    ↑

    | FK id_pedido       | FK id_mesa

Item_Pedido            Mesa (id_mesa PK, numero, capacidade, status)

    |

    | FK id_prato

    ↓

Prato (id_prato PK, nome, categoria, preco)

```



---



## 3. SQL DDL e DML



| Arquivo | Conteúdo |

|---------|----------|

| `sql/01_ddl.sql` | `CREATE DATABASE`, `CREATE TABLE`, `ALTER TABLE`, exemplos de `DROP` |

| `sql/02_dados_exemplo.sql` | `INSERT` com dados de teste |

| `sql/03_dml_exemplos.sql` | `UPDATE`, `DELETE` e `SELECT` de verificação |



### Como executar (Windows)



**Opção recomendada — script automático:**



```powershell

.\setup.ps1

```



**Opção manual — MySQL Workbench:** abra e execute cada `.sql` com ⚡ (ordem 01 → 02 → 03).



**Opção manual — linha de comando:**



```powershell

mysql -u root -p < sql/01_ddl.sql

mysql -u root -p < sql/02_dados_exemplo.sql

```



---



## 4. Três comandos de relatório



Arquivo: `sql/04_relatorios.sql` (também exibidos em `relatorios.php`).



| # | Comandos usados | Descrição |

|---|-----------------|-----------|

| 1 | `WHERE`, `LIKE` | Clientes cujo nome começa com "M" |

| 2 | `COUNT`, `MIN`, `MAX`, `WHERE`, `GROUP BY` | Estatísticas de preço por categoria |

| 3 | `INNER JOIN`, `SUM`, `GROUP BY` | Pedidos com cliente, mesa e valor total |



---



## 5. CRUD web em PHP (3 pontos)



### Configuração



1. Copie `config.example.php` para `config.php` e ajuste a senha do MySQL.

2. Execute `.\setup.ps1` para criar o banco.

3. Execute `.\iniciar.ps1` e acesse `http://localhost:8000`.



### Alternativas ao servidor built-in



- **Laragon:** linkar pasta do projeto em Menu → www → Link to folder.

- **XAMPP:** copiar para `C:\xampp\htdocs\restaurante\` e iniciar Apache.



### Operações CRUD na tabela Cliente



| Operação | Arquivo | Comando SQL |

|----------|---------|-------------|

| **C**reate | `cadastrar.php` | `INSERT INTO Cliente` |

| **R**ead | `listar.php` | `SELECT` com `LEFT JOIN Pedido` |

| **U**pdate | `editar.php` | `UPDATE Cliente SET ... WHERE` |

| **D**elete | `excluir.php` | `DELETE FROM Cliente WHERE` (bloqueado se houver pedidos) |



---



## Estrutura de arquivos



```

restaurante/

├── config.php           # Credenciais MySQL (não versionar)

├── config.example.php   # Modelo de configuração

├── conexao.php          # Conexão com MySQL

├── setup.ps1            # Script de criação do banco (Windows)

├── iniciar.ps1          # Inicia servidor PHP local (porta 8000)

├── index.php            # Menu principal

├── cadastrar.php        # INSERT

├── listar.php           # SELECT

├── editar.php           # UPDATE

├── excluir.php          # DELETE

├── relatorios.php       # 3 relatórios na web

├── sql/

│   ├── 01_ddl.sql

│   ├── 02_dados_exemplo.sql

│   ├── 03_dml_exemplos.sql

│   └── 04_relatorios.sql

└── docs/

    ├── TRABALHO_BD.md   # Este documento

    ├── ENTREGA.md       # Guia de entrega

    ├── EQUIPE.md        # Guia para a equipe

    └── diagramas/

        ├── DIAGRAMA_CONCEITUAL.md

        ├── DIAGRAMA_LOGICO.md

        └── visualizar.html

```



---



## Checklist para entrega



- [ ] Imagem do diagrama conceitual (brModelo ou print de visualizar.html)

- [ ] Imagem do diagrama lógico (MySQL Workbench ou print de visualizar.html)

- [x] Scripts SQL (`sql/`)

- [ ] Print dos 3 relatórios (Workbench ou `relatorios.php`)

- [ ] Print do CRUD funcionando (cadastrar, listar, editar, excluir)

- [ ] Link do repositório: https://github.com/GabrielSTCC/trabalho-academico-banco-de-dados-restaurante



---



## Histórico



| Data | Versão | Descrição |

|------|--------|-----------|

| 23/06/2026 | 1.0 | Versão inicial do trabalho |

| 23/06/2026 | 1.1 | Configuração MySQL local, setup.ps1 e config.php |

| 23/06/2026 | 1.2 | Diagramas, correção CRUD excluir, guia de entrega |
| 23/06/2026 | 1.3 | Guia da equipe (EQUIPE.md), README e instruções genéricas de clone |

