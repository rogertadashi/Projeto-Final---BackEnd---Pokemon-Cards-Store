CREATE DATABASE pokestore;
USE pokestore;

-- Tabela de USUÁRIOS
CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  login VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  funcao ENUM('Administrador','Vendedor') DEFAULT 'Vendedor'
);

-- Tabela de CLIENTES
CREATE TABLE clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100),
  telefone VARCHAR(20),
  cpf VARCHAR(14),
  data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de CARTAS POKÉMON (Produtos)
CREATE TABLE cartas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nome VARCHAR(100) NOT NULL,
  tipo ENUM('Fogo','Água','Planta','Elétrico','Psíquico','Pedra','Metálico','Sombrio','Fada','Normal','Dragão','Outros') DEFAULT 'Outros',
  raridade ENUM('Comum','Incomum','Rara','Ultra Rara','Lendária') DEFAULT 'Comum',
  valor DECIMAL(10,2) NOT NULL,
  imagem VARCHAR(255)
);

-- Tabela de VENDAS
CREATE TABLE vendas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  usuario_id INT NOT NULL,
  data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
  valor_total DECIMAL(10,2) DEFAULT 0,
  condicao_pagamento ENUM('À vista','Pix','Cartão de crédito','Cartão de débito','Parcelado') DEFAULT 'À vista',
  FOREIGN KEY (cliente_id) REFERENCES clientes(id),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

-- Tabela de ITENS DA VENDA
CREATE TABLE itens_venda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venda_id INT NOT NULL,
  carta_id INT NOT NULL,
  quantidade INT DEFAULT 1,
  valor_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (venda_id) REFERENCES vendas(id) ON DELETE CASCADE,
  FOREIGN KEY (carta_id) REFERENCES cartas(id)
);

-- Usuário padrão para login inicial
INSERT INTO usuarios (nome, login, senha, funcao)
VALUES ('Lider', 'elite4', 'admon123', 'Administrador');

