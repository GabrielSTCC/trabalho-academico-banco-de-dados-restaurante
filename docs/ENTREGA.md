# Guia de Entrega — Trabalho de Banco de Dados

## Antes de entregar

1. Preencha seu nome em `docs/TRABALHO_BD.md` (campo _[seu nome]_).
2. Exporte os diagramas oficiais (brModelo + Workbench) ou use prints de `docs/diagramas/visualizar.html`.
3. Tire os prints listados abaixo.
4. Compacte a pasta do projeto em `.zip`.

---

## Como rodar a demonstração

```powershell
cd "c:\Users\gabri\Documents\Trabalho BD\restaurante\restaurante"
.\setup.ps1      # recria banco (se necessário)
.\iniciar.ps1    # inicia servidor PHP
```

Acesse: **http://localhost:8000**

---

## Prints obrigatórios

| # | O que capturar | Onde |
|---|----------------|------|
| 1 | Diagrama conceitual | brModelo ou `docs/diagramas/visualizar.html` |
| 2 | Diagrama lógico (EER) | MySQL Workbench ou `docs/diagramas/visualizar.html` |
| 3 | Relatório 1 (WHERE + LIKE) | `relatorios.php` ou Workbench |
| 4 | Relatório 2 (COUNT, MIN, MAX) | `relatorios.php` ou Workbench |
| 5 | Relatório 3 (JOIN) | `relatorios.php` ou Workbench |
| 6 | Cadastrar cliente | `cadastrar.php` |
| 7 | Listar clientes | `listar.php` |
| 8 | Editar cliente | `editar.php` |
| 9 | Excluir cliente | `excluir.php` (teste ID 4 = sucesso; ID 1 = bloqueio FK) |

---

## Arquivos para incluir no .zip

```
restaurante/
├── config.example.php
├── conexao.php
├── setup.ps1
├── iniciar.ps1
├── index.php
├── cadastrar.php
├── listar.php
├── editar.php
├── excluir.php
├── relatorios.php
├── sql/                    (todos os .sql)
├── docs/
│   ├── TRABALHO_BD.md
│   ├── ENTREGA.md
│   └── diagramas/
└── prints/                 (crie esta pasta com suas imagens)
```

**Não inclua:** `config.php` (contém senha do MySQL).

---

## Ordem sugerida na apresentação

1. Mostrar diagrama conceitual e explicar entidades/relacionamentos.
2. Mostrar diagrama lógico com PKs e FKs.
3. Executar scripts SQL no Workbench (`01_ddl.sql`, `02_dados_exemplo.sql`).
4. Demonstrar os 3 relatórios.
5. Demonstrar CRUD completo no navegador.
6. Explicar restrição de FK na exclusão (cliente com pedidos não pode ser removido).

---

## Checklist final

- [ ] Nome preenchido no documento
- [ ] Diagrama conceitual (PNG)
- [ ] Diagrama lógico (PNG)
- [ ] Scripts SQL incluídos
- [ ] 3 prints de relatórios
- [ ] 4 prints do CRUD
- [ ] Pasta compactada (.zip)
- [ ] `config.php` **fora** do zip
