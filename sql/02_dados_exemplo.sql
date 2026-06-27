-- ============================================================
-- DML: INSERT (dados de exemplo)
-- Execute após 01_ddl.sql
-- ============================================================

USE restaurante;

INSERT INTO Cliente (nome, telefone, email) VALUES
    ('Maria Silva',    '(11) 98765-4321', 'maria@email.com'),
    ('João Santos',    '(11) 91234-5678', 'joao@email.com'),
    ('Ana Oliveira',   '(21) 99876-5432', 'ana@email.com'),
    ('Pedro Costa',    '(31) 97654-3210', 'pedro@email.com'),
    ('Mariana Lima',   '(11) 96543-2109', 'mariana@email.com');

INSERT INTO Mesa (numero, capacidade, status) VALUES
    (1, 2,  'livre'),
    (2, 4,  'ocupada'),
    (3, 4,  'livre'),
    (4, 6,  'reservada'),
    (5, 8,  'livre');

INSERT INTO Prato (nome, categoria, preco) VALUES
    ('Feijoada completa',  'Prato principal', 45.90),
    ('Picanha na chapa',   'Prato principal', 62.50),
    ('Salada Caesar',      'Entrada',         28.00),
    ('Suco de laranja',    'Bebida',          12.00),
    ('Pudim de leite',     'Sobremesa',       15.00),
    ('Refrigerante 350ml', 'Bebida',           8.50);

INSERT INTO Pedido (data_pedido, id_cliente, id_mesa, status) VALUES
    ('2026-06-20 12:30:00', 1, 2, 'fechado'),
    ('2026-06-21 19:15:00', 2, 1, 'fechado'),
    ('2026-06-22 13:00:00', 3, 4, 'aberto'),
    ('2026-06-22 20:45:00', 1, 3, 'aberto'),
    ('2026-06-23 11:00:00', 5, 5, 'cancelado');

INSERT INTO Item_Pedido (id_pedido, id_prato, quantidade, subtotal) VALUES
    (1, 1, 2, 91.80),
    (1, 4, 2, 24.00),
    (2, 3, 1, 28.00),
    (2, 2, 1, 62.50),
    (3, 5, 2, 30.00),
    (3, 6, 3, 25.50),
    (4, 1, 1, 45.90),
    (4, 4, 1, 12.00);
