-- ============================================================
-- 3 COMANDOS DE RELATÓRIO
-- Requisitos: WHERE, COUNT, MIN, MAX, LIKE e 1 com JOIN
-- ============================================================

USE restaurante;

-- ------------------------------------------------------------
-- RELATÓRIO 1 — WHERE + LIKE
-- Clientes cujo nome começa com "M"
-- ------------------------------------------------------------
SELECT id_cliente, nome, telefone, email
FROM Cliente
WHERE nome LIKE 'M%'
ORDER BY nome;

-- ------------------------------------------------------------
-- RELATÓRIO 2 — COUNT, MIN, MAX + WHERE
-- Estatísticas de preço por categoria de prato
-- ------------------------------------------------------------
SELECT
    categoria,
    COUNT(*)        AS total_pratos,
    MIN(preco)      AS preco_minimo,
    MAX(preco)      AS preco_maximo
FROM Prato
WHERE preco > 10
GROUP BY categoria
ORDER BY preco_maximo DESC;

-- ------------------------------------------------------------
-- RELATÓRIO 3 — JOIN (INNER JOIN)
-- Pedidos com nome do cliente, mesa e valor total dos itens
-- ------------------------------------------------------------
SELECT
    p.id_pedido,
    p.data_pedido,
    c.nome              AS cliente,
    m.numero            AS mesa,
    p.status,
    SUM(ip.subtotal)    AS valor_total
FROM Pedido p
INNER JOIN Cliente c      ON c.id_cliente = p.id_cliente
INNER JOIN Mesa m         ON m.id_mesa    = p.id_mesa
INNER JOIN Item_Pedido ip ON ip.id_pedido = p.id_pedido
GROUP BY p.id_pedido, p.data_pedido, c.nome, m.numero, p.status
ORDER BY p.data_pedido DESC;
