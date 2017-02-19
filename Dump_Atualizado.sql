-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 07-Fev-2017 às 06:28
-- Versão do servidor: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `trocarito`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `categoria`
--

CREATE TABLE IF NOT EXISTS `categoria` (
  `id_categoria` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(60) NOT NULL,
  PRIMARY KEY (`id_categoria`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='CATEGORIA' AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nome`) VALUES
(1, 'Categoria 1'),
(2, 'Categoria 2');

-- --------------------------------------------------------

--
-- Estrutura da tabela `doacao`
--

CREATE TABLE IF NOT EXISTS `doacao` (
  `id_doacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_instituicao` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `data` date NOT NULL,
  `valor` float NOT NULL,
  `tipo` enum('D','I') NOT NULL,
  PRIMARY KEY (`id_doacao`),
  KEY `fk_valor_usuario_idx` (`id_usuario`),
  KEY `fk_valor_instituicao_idx` (`id_instituicao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `doacao`
--

INSERT INTO `doacao` (`id_doacao`, `id_instituicao`, `id_usuario`, `data`, `valor`, `tipo`) VALUES
(1, 1, 4, '2017-02-18', 0.05, 'I'),
(2, 2, 5, '2017-02-02', 0.1, 'I'),
(3, 3, 6, '2017-01-01', 5, 'I');

-- --------------------------------------------------------

--
-- Estrutura da tabela `instituicao`
--

CREATE TABLE IF NOT EXISTS `instituicao` (
  `id_instituicao` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nome` varchar(30) DEFAULT NULL,
  `descricao` text,
  PRIMARY KEY (`id_instituicao`),
  KEY `instituicao_usuario_idx` (`id_usuario`),
  KEY `instituicao_categoria_idx` (`id_categoria`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `instituicao`
--

INSERT INTO `instituicao` (`id_instituicao`, `id_usuario`, `id_categoria`, `nome`, `descricao`) VALUES
(1, 4, 1, 'Instituição Mãos Abertas', 'blablabla'),
(2, 5, 2, 'Instituição Deus Nosso', 'kakakakaka'),
(3, 6, 1, 'Instituição Livrança', 'lalalalalalla'),
(4, 1, 1, 'Associação de Proteção a Idoso', 'adsadsadas');

-- --------------------------------------------------------

--
-- Estrutura da tabela `postagem`
--

CREATE TABLE IF NOT EXISTS `postagem` (
  `id_postagem` int(11) NOT NULL AUTO_INCREMENT,
  `id_instituicao` int(11) NOT NULL,
  `conteudo` varchar(200) NOT NULL,
  PRIMARY KEY (`id_postagem`),
  KEY `fk_postagem_instituicao_idx` (`id_instituicao`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Extraindo dados da tabela `postagem`
--

INSERT INTO `postagem` (`id_postagem`, `id_instituicao`, `conteudo`) VALUES
(1, 1, 'dadasdadasd');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) NOT NULL,
  `email` varchar(30) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `nv_caridade` enum('0','1','10','100') DEFAULT NULL,
  `fl_admin` enum('1','0') NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='USUARIO' AUTO_INCREMENT=8 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nome`, `email`, `senha`, `nv_caridade`, `fl_admin`) VALUES
(1, 'Admin', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', '1', '1'),
(4, 'Joao Alves', 'joao@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '0'),
(5, 'jefferson biba', 'jeff@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '0'),
(6, 'Bisneto Alum', 'bis@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '0'),
(7, 'Jaozin', 'joao@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '0');

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `doacao`
--
ALTER TABLE `doacao`
  ADD CONSTRAINT `fk_valor_instituicao` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicao` (`id_instituicao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_valor_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `instituicao`
--
ALTER TABLE `instituicao`
  ADD CONSTRAINT `instituicao_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `postagem`
--
ALTER TABLE `postagem`
  ADD CONSTRAINT `fk_postagem_instituicao` FOREIGN KEY (`id_instituicao`) REFERENCES `instituicao` (`id_instituicao`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
