-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 13-Jun-2024 às 00:34
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `loja_roupa`
--

DELIMITER $$
--
-- Procedimentos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cadastrar_cliente` (IN `p_nomecliente` VARCHAR(80), IN `p_email` VARCHAR(80), IN `p_telefone` VARCHAR(14), IN `p_usuario` VARCHAR(30), IN `p_senha` VARCHAR(100), OUT `p_status` INT, OUT `p_message` VARCHAR(255))   BEGIN
    DECLARE cliente_count INT;
    DECLARE usuario_count INT;

    -- Verificar se o email já existe
    SELECT COUNT(*) INTO cliente_count
    FROM cliente
    WHERE email = p_email;

    IF cliente_count > 0 THEN
        SET p_status = 1;
        SET p_message = 'Email já existe';
    ELSE
        -- Verificar se o telefone já existe
        SELECT COUNT(*) INTO cliente_count
        FROM cliente
        WHERE telefone = p_telefone;

        IF cliente_count > 0 THEN
            SET p_status = 1;
            SET p_message = 'Telefone já existe';
        ELSE
            -- Verificar se o usuário já existe
            SELECT COUNT(*) INTO usuario_count
            FROM usuario
            WHERE usuario = p_usuario;

            IF usuario_count > 0 THEN
                SET p_status = 1;
                SET p_message = 'Usuário já existe';
            ELSE
                -- Inserir dados na tabela cliente
                INSERT INTO cliente (nomecliente, email, telefone)
                VALUES (p_nomecliente, p_email, p_telefone);

                -- Obter o ID do último cliente inserido
                SET @last_id = LAST_INSERT_ID();

                -- Inserir dados na tabela usuario
                INSERT INTO usuario (codcliente_fk, usuario, senha)
                VALUES (@last_id, p_usuario, p_senha);

                -- Definir sucesso
                SET p_status = 0;
                SET p_message = 'Cliente cadastrado com sucesso';
            END IF;
        END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cadastrar_cliente_img` (IN `p_nomecliente` VARCHAR(80), IN `p_email` VARCHAR(80), IN `p_telefone` VARCHAR(14), IN `p_usuario` VARCHAR(30), IN `p_senha` VARCHAR(100), IN `p_pp` VARCHAR(255), OUT `p_status` INT, OUT `p_message` VARCHAR(255))   BEGIN
    DECLARE cliente_count INT;
    DECLARE usuario_count INT;

    -- Verificar se o email já existe
    SELECT COUNT(*) INTO cliente_count
    FROM cliente
    WHERE email = p_email;

    IF cliente_count > 0 THEN
        SET p_status = 1;
        SET p_message = 'Email já existe';
    ELSE
        -- Verificar se o telefone já existe
        SELECT COUNT(*) INTO cliente_count
        FROM cliente
        WHERE telefone = p_telefone;

        IF cliente_count > 0 THEN
            SET p_status = 1;
            SET p_message = 'Telefone já existe';
        ELSE
            -- Verificar se o usuário já existe
            SELECT COUNT(*) INTO usuario_count
            FROM usuario
            WHERE usuario = p_usuario;

            IF usuario_count > 0 THEN
                SET p_status = 1;
                SET p_message = 'Usuário já existe';
            ELSE
                -- Inserir dados na tabela cliente
                INSERT INTO cliente (nomecliente, email, telefone, pp)
                VALUES (p_nomecliente, p_email, p_telefone, p_pp);

                -- Obter o ID do último cliente inserido
                SET @last_id = LAST_INSERT_ID();

                -- Inserir dados na tabela usuario
                INSERT INTO usuario (codcliente_fk, usuario, senha)
                VALUES (@last_id, p_usuario, p_senha);

                -- Definir sucesso
                SET p_status = 0;
                SET p_message = 'Cliente cadastrado com sucesso';
            END IF;
        END IF;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InserirVenda` (IN `codcliente` INT)   BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE codproduto_fk_var INT;
    DECLARE quantidade_var INT;
    DECLARE valor_unit_var DECIMAL(10,2);

    DECLARE cur CURSOR FOR
        SELECT codproduto_fk, quantidade, valor_unit
        FROM carrinho
        WHERE codcliente_fk = codcliente;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
    
    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO codproduto_fk_var, quantidade_var, valor_unit_var;
        
        IF done THEN
            LEAVE read_loop;
        END IF;

        INSERT INTO venda (codcliente_fk, codproduto_fk, quantidade, valor_unit)
        VALUES (codcliente, codproduto_fk_var, quantidade_var, valor_unit_var);
        
        DELETE FROM carrinho
        WHERE codcliente_fk = codcliente AND codproduto_fk = codproduto_fk_var;
    END LOOP;
    
    CLOSE cur;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `carrinho`
--

CREATE TABLE `carrinho` (
  `codcliente_fk` int(11) NOT NULL,
  `codproduto_fk` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unit` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE `categoria` (
  `codcategoria` int(11) NOT NULL,
  `nome_categoria` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`codcategoria`, `nome_categoria`) VALUES
(1, 'Camiseta'),
(2, 'Short'),
(3, 'Jaquetas & Moletons');

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `codcliente` int(11) NOT NULL,
  `nomecliente` varchar(80) NOT NULL,
  `email` varchar(80) NOT NULL,
  `telefone` varchar(14) NOT NULL,
  `pp` varchar(255) NOT NULL DEFAULT 'default-pp.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`codcliente`, `nomecliente`, `email`, `telefone`, `pp`) VALUES
(29, 'Viniciuis', 'vinicius@hotmail.com', '(17) 99192-034', 'root66524c26a79724.12536063.jpeg'),
(30, 'Douglas', 'douglas@gmail.com', '(17) 91231-232', 'root66550af0b0b238.54027326.jpeg'),
(31, 'Gabriel', 'gabrielribeiromaschio@hotmail.com', '(17) 99192-008', 'root6655346c2321c1.46314391.jpeg'),
(32, 'Breno', 'breno@hotmail.com', '(19) 99192-098', 'breno6655f06b46f5e6.00690825.jpeg'),
(43, 'THiago', 'thiago@gmail.co', '(12) 12123-123', 'thiago6660ed789b9bd2.26100268.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `produto`
--

CREATE TABLE `produto` (
  `codproduto` int(11) NOT NULL,
  `nomeproduto` varchar(80) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor` float NOT NULL,
  `desconto` int(11) NOT NULL,
  `codtipo_fk` int(11) NOT NULL,
  `codcategoria_fk` int(11) NOT NULL,
  `pp` varchar(255) NOT NULL DEFAULT 'default-pp.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `produto`
--

INSERT INTO `produto` (`codproduto`, `nomeproduto`, `quantidade`, `valor`, `desconto`, `codtipo_fk`, `codcategoria_fk`, `pp`) VALUES
(18, 'Camiseta NikeCourt Heritage Masculina', 10, 284.99, 5, 3, 1, 'Camiseta NikeCourt Heritage Masculina66594efb59bc03.43363887.png'),
(19, 'Camiseta Nike Sportswear Masculina', 10, 189.99, 17, 1, 1, 'Camiseta Nike Sportswear Masculina665956a895f091.91948205.png'),
(20, 'Camiseta Nike Vintage Masculina', 5, 218.49, 5, 3, 1, 'Camiseta Nike Vintage Masculina66595d40dd69b2.16682644.png'),
(21, 'Camiseta Jordan Masculina', 8, 199.49, 40, 1, 1, 'Camiseta Jordan Masculina66595d68372063.43369823.png'),
(22, 'Camiseta Nike Dri-FIT Masculina', 16, 208.99, 9, 3, 1, 'Camiseta Nike Dri-FIT Masculina66595d986bc6f1.70711274.png'),
(23, 'Shorts Nike Dri-FIT Form Masculino', 4, 284.99, 5, 3, 2, 'Shorts Nike Dri-FIT Form Masculino66595e89370126.82904541.png'),
(29, 'Shorts Nike Split Volley Masculino', 7, 256.49, 5, 5, 2, 'Shorts Nike Split Volley Masculino66595ee4879666.14430885.png'),
(30, 'Shorts Nike Club Fleece Masculino', 22, 189.99, 46, 1, 2, 'Shorts Nike Club Fleece Masculino66595f261a9569.78082154.png'),
(31, 'Shorts Nike Dri-FIT Academy 23 Masculino', 9, 113.99, 24, 2, 2, 'Shorts Nike Dri-FIT Academy 23 Masculino66595fc6d24640.67955129.png'),
(44, 'Shorts Nike Dri-FIT Challenger Masculino', 31, 284.99, 5, 6, 2, 'Shorts Nike Dri-FIT Challenger Masculino66596126764351.28155089.png'),
(45, 'Jaqueta Nike Dri-FIT Team Woven Masculina', 22, 294.49, 26, 3, 3, 'Jaqueta Nike Dri-FIT Team Woven Masculina665b907d713947.08161370.png');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo`
--

CREATE TABLE `tipo` (
  `codtipo` int(11) NOT NULL,
  `nometipo` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `tipo`
--

INSERT INTO `tipo` (`codtipo`, `nometipo`) VALUES
(1, 'Casual'),
(6, 'Corrida'),
(2, 'Futebol'),
(5, 'Natação & Praia'),
(3, 'Treino & Academia');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `codcliente_fk` int(11) NOT NULL,
  `usuario` varchar(30) NOT NULL,
  `senha` varchar(180) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`codcliente_fk`, `usuario`, `senha`) VALUES
(29, 'vinicius', '$2y$10$ZGJeLV1y9l9OF2bNcDg6/.89AkE4TZGMleHi7Q.zI67vu6jKMbIUq'),
(30, 'douglas', '$2y$10$DsXoGfYZKGt7E8QfqYd/8eQZ/DWZ6SgxN.M3cemvXG4fAXs2X05H6'),
(31, 'gabriel', '$2y$10$aOMGjhSkUCDR9h/2NBZCBeO6K0ZHpbm6Fpb.3arTwLjhur7dY.29a'),
(32, 'breno', '$2y$10$iLp8SvQdkz.id2Lm8cVS3OhLl2YOQKNnJmYId05kkzDs8.ZFhfh9O'),
(43, 'thiago', '$2y$10$KM.QueFZwjr90Qhe2ZuW9.w2WOYVH/0CFNlG9njDPsLt66jbmBSv.');

-- --------------------------------------------------------

--
-- Estrutura da tabela `venda`
--

CREATE TABLE `venda` (
  `codvenda` int(11) NOT NULL,
  `codcliente_fk` int(11) NOT NULL,
  `codproduto_fk` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `valor_unit` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `venda`
--

INSERT INTO `venda` (`codvenda`, `codcliente_fk`, `codproduto_fk`, `quantidade`, `valor_unit`) VALUES
(1, 29, 30, 2, 189.99),
(2, 29, 45, 1, 294.49),
(3, 31, 18, 1, 284.99),
(4, 31, 31, 1, 113.99),
(5, 31, 19, 1, 189.99),
(6, 31, 18, 1, 284.99),
(7, 31, 29, 1, 256.49),
(8, 31, 30, 1, 189.99),
(9, 31, 31, 1, 113.99),
(10, 31, 19, 1, 189.99),
(11, 31, 20, 1, 218.49),
(12, 43, 20, 1, 218.49),
(13, 43, 21, 1, 199.49),
(14, 31, 19, 1, 189.99),
(15, 31, 23, 1, 284.99),
(16, 31, 30, 1, 189.99),
(17, 31, 18, 1, 284.99);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD PRIMARY KEY (`codcliente_fk`,`codproduto_fk`);

--
-- Índices para tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`codcategoria`);

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`codcliente`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `telefone` (`telefone`);

--
-- Índices para tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`codproduto`),
  ADD KEY `codtipo_fk` (`codtipo_fk`),
  ADD KEY `codcategoria_fk` (`codcategoria_fk`);

--
-- Índices para tabela `tipo`
--
ALTER TABLE `tipo`
  ADD PRIMARY KEY (`codtipo`),
  ADD UNIQUE KEY `nometipo` (`nometipo`);

--
-- Índices para tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`codcliente_fk`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Índices para tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`codvenda`,`codcliente_fk`,`codproduto_fk`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `codcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `codcliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `codproduto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de tabela `tipo`
--
ALTER TABLE `tipo`
  MODIFY `codtipo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `codvenda` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `carrinho`
--
ALTER TABLE `carrinho`
  ADD CONSTRAINT `id_produto` FOREIGN KEY (`codproduto_fk`) REFERENCES `produto` (`codproduto`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_usuario` FOREIGN KEY (`codcliente_fk`) REFERENCES `cliente` (`codcliente`);

--
-- Limitadores para a tabela `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `codcategoria_fk` FOREIGN KEY (`codcategoria_fk`) REFERENCES `categoria` (`codcategoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `codtipo_fk` FOREIGN KEY (`codtipo_fk`) REFERENCES `tipo` (`codtipo`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `codcliente_fk` FOREIGN KEY (`codcliente_fk`) REFERENCES `cliente` (`codcliente`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
