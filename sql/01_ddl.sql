-- ============================================================
-- TRABALHO DE BANCO DE DADOS - SISTEMA RESTAURANTE
-- DDL: CREATE, ALTER, DROP
-- ============================================================

DROP DATABASE IF EXISTS restaurante;
CREATE DATABASE restaurante CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE restaurante;

-- ------------------------------------------------------------
-- CREATE TABLE
-- ------------------------------------------------------------

CREATE TABLE Cliente (
    id_cliente   INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(100) NOT NULL,
    telefone     VARCHAR(20),
    email        VARCHAR(100)
);

CREATE TABLE Mesa (
    id_mesa      INT AUTO_INCREMENT PRIMARY KEY,
    numero       INT NOT NULL UNIQUE,
    capacidade   INT NOT NULL,
    status       ENUM('livre', 'ocupada', 'reservada') NOT NULL DEFAULT 'livre'
);

CREATE TABLE Prato (
    id_prato     INT AUTO_INCREMENT PRIMARY KEY,
    nome         VARCHAR(100) NOT NULL,
    categoria    VARCHAR(50),
    preco        DECIMAL(10, 2) NOT NULL
);

CREATE TABLE Pedido (
    id_pedido    INT AUTO_INCREMENT PRIMARY KEY,
    data_pedido  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    id_cliente   INT,
    id_mesa      INT,
    status       ENUM('aberto', 'fechado', 'cancelado') NOT NULL DEFAULT 'aberto',
    CONSTRAINT fk_pedido_cliente FOREIGN KEY (id_cliente) REFERENCES Cliente(id_cliente),
    CONSTRAINT fk_pedido_mesa    FOREIGN KEY (id_mesa)    REFERENCES Mesa(id_mesa)
);

CREATE TABLE Item_Pedido (
    id_item      INT AUTO_INCREMENT PRIMARY KEY,
    id_pedido    INT NOT NULL,
    id_prato     INT NOT NULL,
    quantidade   INT NOT NULL DEFAULT 1,
    subtotal     DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_item_pedido FOREIGN KEY (id_pedido) REFERENCES Pedido(id_pedido),
    CONSTRAINT fk_item_prato  FOREIGN KEY (id_prato)  REFERENCES Prato(id_prato)
);

-- ------------------------------------------------------------
-- ALTER TABLE (exemplo: nova coluna em Cliente)
-- ------------------------------------------------------------

ALTER TABLE Cliente
    ADD COLUMN data_cadastro DATE NOT NULL DEFAULT (CURRENT_DATE);

ALTER TABLE Prato
    MODIFY COLUMN preco DECIMAL(10, 2) NOT NULL DEFAULT 0.00;

-- ------------------------------------------------------------
-- DROP (exemplos — descomente apenas para demonstrar em aula)
-- ------------------------------------------------------------

-- DROP TABLE IF EXISTS Item_Pedido;
-- DROP TABLE IF EXISTS Pedido;
-- DROP TABLE IF EXISTS Prato;
-- DROP TABLE IF EXISTS Mesa;
-- DROP TABLE IF EXISTS Cliente;
