-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 26/07/2025 às 13:19
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `rentgo`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `agendamentos_locacao`
--

CREATE TABLE `agendamentos_locacao` (
  `agen_codigo` int(11) NOT NULL,
  `agen_datainicio` date DEFAULT NULL,
  `agen_datafim` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `car_codigo` int(11) NOT NULL,
  `cli_codigo` int(11) NOT NULL,
  `prod_codigo` int(11) NOT NULL,
  `car_quantidade` int(11) DEFAULT 1,
  `car_tipo` enum('compra','locacao') NOT NULL,
  `car_data_adicao` datetime NOT NULL DEFAULT current_timestamp(),
  `car_data_inicio_locacao` date DEFAULT NULL,
  `car_data_fim_locacao` date DEFAULT NULL,
  `car_status` enum('ativo','finalizado') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `carrinho`
--

INSERT INTO `carrinho` (`car_codigo`, `cli_codigo`, `prod_codigo`, `car_quantidade`, `car_tipo`, `car_data_adicao`, `car_data_inicio_locacao`, `car_data_fim_locacao`, `car_status`) VALUES
(2, 2, 3, 1, '', '2025-07-25 16:33:06', NULL, NULL, 'finalizado'),
(3, 2, 3, 1, '', '2025-07-25 16:42:54', NULL, NULL, 'finalizado');

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `cat_codigo` int(11) NOT NULL,
  `cat_nome` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`cat_codigo`, `cat_nome`) VALUES
(1, 'Roupas'),
(2, 'Eletrônicos'),
(3, 'Automóveis'),
(4, 'Imóveis');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `cli_codigo` int(11) NOT NULL,
  `cli_cpf` varchar(45) NOT NULL,
  `cli_nome` varchar(60) NOT NULL,
  `cli_email` varchar(60) NOT NULL,
  `cli_senha` varchar(40) NOT NULL,
  `cli_telefone` varchar(14) NOT NULL,
  `cli_endereco` varchar(60) NOT NULL,
  `cli_datanasc` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`cli_codigo`, `cli_cpf`, `cli_nome`, `cli_email`, `cli_senha`, `cli_telefone`, `cli_endereco`, `cli_datanasc`) VALUES
(1, '123', 'g', 'g@g.com', '202cb962ac59075b964b07152d234b70', '123', '123', '2007-09-21'),
(2, '123', 'a', 'a@a.com', '202cb962ac59075b964b07152d234b70', '123', '123', '0000-00-00');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feedback`
--

CREATE TABLE `feedback` (
  `fed_codigo` int(11) NOT NULL,
  `fed_desc` varchar(45) DEFAULT NULL,
  `fed_avaliacao` float DEFAULT NULL,
  `cliente_cli_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_compras`
--

CREATE TABLE `historico_compras` (
  `his_codigo` int(11) NOT NULL,
  `cliente_cli_codigo` int(11) NOT NULL,
  `pag_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `itens_pedido`
--

CREATE TABLE `itens_pedido` (
  `itemPed_codigo` int(11) NOT NULL,
  `itemPed_quantidade` int(11) DEFAULT NULL,
  `itemPed_precoUnitario` decimal(10,2) DEFAULT NULL,
  `pedido_ped_codigo` int(11) NOT NULL,
  `prod_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itens_pedido`
--

INSERT INTO `itens_pedido` (`itemPed_codigo`, `itemPed_quantidade`, `itemPed_precoUnitario`, `pedido_ped_codigo`, `prod_codigo`) VALUES
(1, 1, 2500.00, 1, 3),
(2, 1, 2500.00, 2, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `marca`
--

CREATE TABLE `marca` (
  `marca_codigo` int(11) NOT NULL,
  `marca_nome` varchar(50) NOT NULL,
  `cat_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `marca`
--

INSERT INTO `marca` (`marca_codigo`, `marca_nome`, `cat_codigo`) VALUES
(1, 'Samsung', 0),
(2, 'Apple', 0),
(3, 'Sony', 0),
(4, 'LG', 0),
(5, 'Toyota', 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `modelo`
--

CREATE TABLE `modelo` (
  `modelo_codigo` int(11) NOT NULL,
  `modelo_nome` varchar(50) NOT NULL,
  `marca_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Despejando dados para a tabela `modelo`
--

INSERT INTO `modelo` (`modelo_codigo`, `modelo_nome`, `marca_codigo`) VALUES
(1, 'Galaxy S21', 1),
(2, 'Galaxy A52', 1),
(3, 'iPhone 13', 2),
(4, 'iPhone SE', 2),
(5, 'Xperia 5', 3),
(6, 'Bravia X90J', 3),
(7, 'OLED CX', 4),
(8, 'Gram 17', 4),
(9, 'Corolla', 5),
(10, 'Hilux', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `pag_codigo` int(11) NOT NULL,
  `pag_metodo` varchar(45) DEFAULT NULL,
  `pag_data` date DEFAULT NULL,
  `pag_status` varchar(45) DEFAULT NULL,
  `pedido_ped_codigo` int(11) DEFAULT NULL,
  `agen_codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedido`
--

CREATE TABLE `pedido` (
  `ped_codigo` int(11) NOT NULL,
  `ped_data` varchar(45) NOT NULL,
  `ped_status` varchar(45) DEFAULT NULL,
  `ped_tipo` varchar(45) NOT NULL,
  `cli_codigo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pedido`
--

INSERT INTO `pedido` (`ped_codigo`, `ped_data`, `ped_status`, `ped_tipo`, `cli_codigo`) VALUES
(1, '2025-07-25 16:41:47', 'processando', 'compra/locacao', 2),
(2, '2025-07-25 16:42:59', 'processando', 'compra/locacao', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos_servicos`
--

CREATE TABLE `produtos_servicos` (
  `prod_codigo` int(11) NOT NULL,
  `prod_nome` varchar(60) NOT NULL,
  `prod_descricao` varchar(100) NOT NULL,
  `prod_valor` decimal(10,2) NOT NULL,
  `prod_tipo` varchar(20) NOT NULL,
  `prod_disponivel` varchar(20) NOT NULL,
  `prod_datacriacao` datetime NOT NULL,
  `vend_codigo` int(11) NOT NULL,
  `cat_codigo` int(11) NOT NULL,
  `prod_imagem` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos_servicos`
--

INSERT INTO `produtos_servicos` (`prod_codigo`, `prod_nome`, `prod_descricao`, `prod_valor`, `prod_tipo`, `prod_disponivel`, `prod_datacriacao`, `vend_codigo`, `cat_codigo`, `prod_imagem`) VALUES
(3, 'Samsung Galaxy A52', 'celular em ótimo estado', 2500.00, 'Eletrônicos', 'Sim', '2025-07-24 15:26:04', 6, 2, 'php/uploads/prod_6882346c78772.jpeg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `vendedor`
--

CREATE TABLE `vendedor` (
  `vend_codigo` int(11) NOT NULL,
  `vend_nome` varchar(45) NOT NULL,
  `vend_email` varchar(45) NOT NULL,
  `vend_senha` varchar(32) NOT NULL,
  `vend_telefone` varchar(24) NOT NULL,
  `vend_endereco` varchar(60) NOT NULL,
  `vend_datanasc` date NOT NULL,
  `vend_cpf_cnpj` varchar(30) NOT NULL,
  `feedback_fed_codigo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `vendedor`
--

INSERT INTO `vendedor` (`vend_codigo`, `vend_nome`, `vend_email`, `vend_senha`, `vend_telefone`, `vend_endereco`, `vend_datanasc`, `vend_cpf_cnpj`, `feedback_fed_codigo`) VALUES
(3, 'Guilherme', 'guilherme99santiago@gmail.com', '202cb962ac59075b964b07152d234b70', '18996632725', 'rua das flores', '2007-09-21', '', NULL),
(4, 'g', 'g@g.com', '202cb962ac59075b964b07152d234b70', '123', 'rua 123', '2007-09-21', '', NULL),
(6, 'GUILHERME SANTIAGO BERGAMINI', 'g@gg.com', '202cb962ac59075b964b07152d234b70', '123', 'Cipriano Limeira', '1111-11-11', '', NULL),
(7, 'ca', 'ca@c.com', '202cb962ac59075b964b07152d234b70', '213', 'rya 123', '1111-01-01', '', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `agendamentos_locacao`
--
ALTER TABLE `agendamentos_locacao`
  ADD PRIMARY KEY (`agen_codigo`);

--
-- Índices de tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`car_codigo`),
  ADD KEY `cli_codigo` (`cli_codigo`),
  ADD KEY `prod_codigo` (`prod_codigo`);

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`cat_codigo`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`cli_codigo`);

--
-- Índices de tabela `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`fed_codigo`),
  ADD KEY `fk_feedback_cliente1` (`cliente_cli_codigo`);

--
-- Índices de tabela `historico_compras`
--
ALTER TABLE `historico_compras`
  ADD PRIMARY KEY (`his_codigo`),
  ADD KEY `fk_hist_comp_cliente` (`cliente_cli_codigo`),
  ADD KEY `fk_hist_comp_pagamento` (`pag_codigo`);

--
-- Índices de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD PRIMARY KEY (`itemPed_codigo`),
  ADD KEY `fk_itens_pedido` (`pedido_ped_codigo`),
  ADD KEY `fk_itens_produto` (`prod_codigo`);

--
-- Índices de tabela `marca`
--
ALTER TABLE `marca`
  ADD PRIMARY KEY (`marca_codigo`);

--
-- Índices de tabela `modelo`
--
ALTER TABLE `modelo`
  ADD PRIMARY KEY (`modelo_codigo`),
  ADD KEY `fk_marca_modelo` (`marca_codigo`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`pag_codigo`),
  ADD KEY `fk_pagamentos_pedido1` (`pedido_ped_codigo`),
  ADD KEY `fk_pagamentos_agendamento1` (`agen_codigo`);

--
-- Índices de tabela `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`ped_codigo`),
  ADD KEY `fk_pedido_cliente1` (`cli_codigo`);

--
-- Índices de tabela `produtos_servicos`
--
ALTER TABLE `produtos_servicos`
  ADD PRIMARY KEY (`prod_codigo`),
  ADD KEY `fk_produtos_vendedor` (`vend_codigo`),
  ADD KEY `fk_produtos_categoria` (`cat_codigo`);

--
-- Índices de tabela `vendedor`
--
ALTER TABLE `vendedor`
  ADD PRIMARY KEY (`vend_codigo`),
  ADD KEY `fk_vendedor_feedback1` (`feedback_fed_codigo`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `agendamentos_locacao`
--
ALTER TABLE `agendamentos_locacao`
  MODIFY `agen_codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `carrinho`
--
ALTER TABLE `carrinho`
  MODIFY `car_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `cat_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `cli_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `feedback`
--
ALTER TABLE `feedback`
  MODIFY `fed_codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `historico_compras`
--
ALTER TABLE `historico_compras`
  MODIFY `his_codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `itens_pedido`
--
ALTER TABLE `itens_pedido`
  MODIFY `itemPed_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `marca`
--
ALTER TABLE `marca`
  MODIFY `marca_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `modelo`
--
ALTER TABLE `modelo`
  MODIFY `modelo_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `pag_codigo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedido`
--
ALTER TABLE `pedido`
  MODIFY `ped_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `produtos_servicos`
--
ALTER TABLE `produtos_servicos`
  MODIFY `prod_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `vendedor`
--
ALTER TABLE `vendedor`
  MODIFY `vend_codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `carrinho_ibfk_1` FOREIGN KEY (`cli_codigo`) REFERENCES `cliente` (`cli_codigo`),
  ADD CONSTRAINT `carrinho_ibfk_2` FOREIGN KEY (`prod_codigo`) REFERENCES `produtos_servicos` (`prod_codigo`);

--
-- Restrições para tabelas `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `fk_feedback_cliente1` FOREIGN KEY (`cliente_cli_codigo`) REFERENCES `cliente` (`cli_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `historico_compras`
--
ALTER TABLE `historico_compras`
  ADD CONSTRAINT `fk_hist_comp_cliente` FOREIGN KEY (`cliente_cli_codigo`) REFERENCES `cliente` (`cli_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_hist_comp_pagamento` FOREIGN KEY (`pag_codigo`) REFERENCES `pagamentos` (`pag_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `itens_pedido`
--
ALTER TABLE `itens_pedido`
  ADD CONSTRAINT `fk_itens_pedido` FOREIGN KEY (`pedido_ped_codigo`) REFERENCES `pedido` (`ped_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_itens_produto` FOREIGN KEY (`prod_codigo`) REFERENCES `produtos_servicos` (`prod_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `modelo`
--
ALTER TABLE `modelo`
  ADD CONSTRAINT `fk_marca_modelo` FOREIGN KEY (`marca_codigo`) REFERENCES `marca` (`marca_codigo`);

--
-- Restrições para tabelas `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `fk_pagamentos_agendamento1` FOREIGN KEY (`agen_codigo`) REFERENCES `agendamentos_locacao` (`agen_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pagamentos_pedido1` FOREIGN KEY (`pedido_ped_codigo`) REFERENCES `pedido` (`ped_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `fk_pedido_cliente1` FOREIGN KEY (`cli_codigo`) REFERENCES `cliente` (`cli_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `produtos_servicos`
--
ALTER TABLE `produtos_servicos`
  ADD CONSTRAINT `fk_produtos_categoria` FOREIGN KEY (`cat_codigo`) REFERENCES `categoria` (`cat_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_produtos_vendedor` FOREIGN KEY (`vend_codigo`) REFERENCES `vendedor` (`vend_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Restrições para tabelas `vendedor`
--
ALTER TABLE `vendedor`
  ADD CONSTRAINT `fk_vendedor_feedback1` FOREIGN KEY (`feedback_fed_codigo`) REFERENCES `feedback` (`fed_codigo`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
