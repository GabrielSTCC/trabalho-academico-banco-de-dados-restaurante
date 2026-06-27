-- ============================================================
-- DML: UPDATE e DELETE (exemplos)
-- ============================================================

USE restaurante;

-- UPDATE: alterar telefone de um cliente
UPDATE Cliente
SET telefone = '(11) 90000-0000'
WHERE id_cliente = 1;

-- UPDATE: fechar pedido em aberto
UPDATE Pedido
SET status = 'fechado'
WHERE id_pedido = 3;

-- UPDATE: corrigir preço de um prato
UPDATE Prato
SET preco = 29.90
WHERE nome LIKE '%Caesar%';

-- DELETE: remover item de pedido cancelado
DELETE FROM Item_Pedido
WHERE id_pedido = 5;

-- DELETE: remover pedido cancelado (sem itens)
DELETE FROM Pedido
WHERE id_pedido = 5 AND status = 'cancelado';

-- SELECT básico (consulta de verificação)
SELECT * FROM Cliente;
SELECT * FROM Pedido WHERE status = 'aberto';
