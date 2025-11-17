CREATE DATABASE IF NOT EXISTS pokestore
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE pokestore;

-- Table Usuários (Admin/Vendedor):
CREATE TABLE IF NOT EXISTS usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  login VARCHAR(50) NOT NULL UNIQUE,
  senha VARCHAR(255) NOT NULL,
  funcao ENUM('Administrador', 'Vendedor') NOT NULL DEFAULT 'Vendedor',
  criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table Clientes:
CREATE TABLE IF NOT EXISTS clientes (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE,
  telefone VARCHAR(20),
  cpf VARCHAR(14) UNIQUE,
  login VARCHAR(50) UNIQUE NOT NULL,
  senha VARCHAR(255) NOT NULL,
  data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de cartas
CREATE TABLE IF NOT EXISTS cartas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR(20) NOT NULL UNIQUE,
  nome VARCHAR(100) NOT NULL,
  tipo ENUM(
    'Fogo','Água','Planta','Elétrico','Psíquico','Pedra',
    'Metálico','Sombrio','Fada','Normal','Dragão','Outros'
  ) DEFAULT 'Outros',
  raridade ENUM('Comum','Incomum','Rara','Ultra Rara','Lendária') DEFAULT 'Comum',
  valor DECIMAL(10,2) NOT NULL CHECK (valor >= 0),
  estoque INT NOT NULL DEFAULT 0 CHECK (estoque >= 0),
  imagem VARCHAR(255),
  data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de vendas
CREATE TABLE IF NOT EXISTS vendas (
  id INT AUTO_INCREMENT PRIMARY KEY,
  cliente_id INT NOT NULL,
  data_venda DATETIME DEFAULT CURRENT_TIMESTAMP,
  valor_total DECIMAL(10,2) DEFAULT 0 CHECK (valor_total >= 0),
  condicao_pagamento ENUM('À vista','Pix','Cartão de crédito','Cartão de débito','Parcelado') DEFAULT 'À vista',
  FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de itens de venda
CREATE TABLE IF NOT EXISTS itens_venda (
  id INT AUTO_INCREMENT PRIMARY KEY,
  venda_id INT NOT NULL,
  carta_id INT NOT NULL,
  quantidade INT DEFAULT 1 CHECK (quantidade > 0),
  valor_unitario DECIMAL(10,2) NOT NULL CHECK (valor_unitario >= 0),
  FOREIGN KEY (venda_id) REFERENCES vendas(id) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (carta_id) REFERENCES cartas(id) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabela de fornecedores
CREATE TABLE IF NOT EXISTS fornecedores (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(120) NOT NULL,
  contato VARCHAR(120),
  telefone VARCHAR(40),
  email VARCHAR(120),
  data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert nde usuario admin
INSERT INTO usuarios (nome, login, senha, funcao)
VALUES ('Líder', 'elite4', 'admon123', 'Administrador')
ON DUPLICATE KEY UPDATE
  nome = VALUES(nome),
  senha = VALUES(senha),
  funcao = VALUES(funcao);
