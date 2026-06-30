<?php

function sincronizarStatusMesa($conexao, $id_mesa, $statusPedido) {
    if ($id_mesa <= 0) {
        return;
    }
    if ($statusPedido === 'aberto') {
        $conexao->query("UPDATE Mesa SET status = 'ocupada' WHERE id_mesa = $id_mesa");
    } else {
        $conexao->query("UPDATE Mesa SET status = 'livre' WHERE id_mesa = $id_mesa");
    }
}

function sincronizarTodasMesas($conexao) {
    $conexao->query(
        "UPDATE Mesa m
         INNER JOIN Pedido p ON p.id_mesa = m.id_mesa AND p.status = 'aberto'
         SET m.status = 'ocupada'"
    );
    $conexao->query(
        "UPDATE Mesa
         SET status = 'livre'
         WHERE status = 'ocupada'
           AND id_mesa NOT IN (
               SELECT id_mesa FROM (
                   SELECT id_mesa FROM Pedido WHERE status = 'aberto' AND id_mesa IS NOT NULL
               ) AS mesas_ocupadas
           )"
    );
}

function statusExibidoMesa($statusMesa, $temPedidoAberto) {
    return $temPedidoAberto ? 'ocupada' : $statusMesa;
}
