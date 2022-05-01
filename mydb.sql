-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Ned 24. dub 2022, 09:58
-- Verze serveru: 5.7.31
-- Verze PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `mydb`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `kategorieznamek`
--

DROP TABLE IF EXISTS `kategorieznamek`;
CREATE TABLE IF NOT EXISTS `kategorieznamek` (
  `idKategorieZnamek` int(11) NOT NULL AUTO_INCREMENT,
  `vaha` float NOT NULL,
  `nazev` varchar(45) COLLATE utf16_czech_ci NOT NULL,
  `ucitel_ucet_idLogin` int(11) NOT NULL,
  `predmetTridy_idpredmetTridy` int(11) NOT NULL,
  PRIMARY KEY (`idKategorieZnamek`),
  UNIQUE KEY `idKategorieZnamek_UNIQUE` (`idKategorieZnamek`),
  KEY `fk_kategorieZnamek_ucitel1_idx` (`ucitel_ucet_idLogin`),
  KEY `fk_kategorieZnamek_predmetTridy1_idx` (`predmetTridy_idpredmetTridy`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `kategorieznamek`
--


-- --------------------------------------------------------

--
-- Struktura tabulky `predmet`
--

DROP TABLE IF EXISTS `predmet`;
CREATE TABLE IF NOT EXISTS `predmet` (
  `zkratka` varchar(5) COLLATE utf16_czech_ci NOT NULL,
  `nazev` varchar(45) COLLATE utf16_czech_ci NOT NULL,
  PRIMARY KEY (`zkratka`),
  UNIQUE KEY `nazev_UNIQUE` (`nazev`),
  UNIQUE KEY `zkratka_UNIQUE` (`zkratka`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `predmet`
--

INSERT INTO `predmet` (`zkratka`, `nazev`) VALUES
('AJ', 'Anglický jazyk'),
('CJ', 'Český jazyk'),
('DB', 'Databáze'),
('EN', 'Ekonomika'),
('HW', 'Hardware'),
('IN', 'Informatika'),
('MA', 'Matematika'),
('MU', 'Multimédia'),
('PS', 'Počítačové sítě'),
('PG', 'Programování'),
('SV', 'Společenské vědy'),
('TV', 'Tělesná výchova'),
('WA', 'Webové aplikace');

-- --------------------------------------------------------

--
-- Struktura tabulky `predmettridy`
--

DROP TABLE IF EXISTS `predmettridy`;
CREATE TABLE IF NOT EXISTS `predmettridy` (
  `skupinaZaku_idSkupiny` int(11) NOT NULL,
  `ucitel_ucet_idLogin` int(11) NOT NULL,
  `idpredmetTridy` int(11) NOT NULL,
  `predmet_zkratka` varchar(5) COLLATE utf16_czech_ci NOT NULL,
  PRIMARY KEY (`idpredmetTridy`),
  UNIQUE KEY `idpredmetTridy_UNIQUE` (`idpredmetTridy`),
  KEY `fk_predmetTridy_skupinaZaku1_idx` (`skupinaZaku_idSkupiny`),
  KEY `fk_predmetTridy_ucitel1_idx` (`ucitel_ucet_idLogin`),
  KEY `fk_predmetTridy_predmet1_idx` (`predmet_zkratka`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `predmettridy`
--

INSERT INTO `predmettridy` (`skupinaZaku_idSkupiny`, `ucitel_ucet_idLogin`, `idpredmetTridy`, `predmet_zkratka`) VALUES
(1, 16, 1, 'CJ'),
(1, 17, 2, 'MA'),
(1, 20, 3, 'AJ'),
(1, 19, 4, 'TV'),
(2, 16, 5, 'PS'),
(2, 17, 6, 'HW'),
(3, 18, 7, 'PS'),
(3, 20, 8, 'HW'),
(4, 20, 9, 'CJ'),
(4, 19, 10, 'MA'),
(4, 17, 11, 'AJ'),
(4, 18, 12, 'TV'),
(5, 16, 13, 'EN'),
(5, 17, 14, 'SV'),
(6, 18, 15, 'EN'),
(6, 19, 16, 'SV'),
(7, 19, 17, 'CJ'),
(7, 20, 18, 'MA'),
(7, 18, 19, 'AJ'),
(7, 16, 20, 'TV'),
(8, 16, 21, 'PG'),
(8, 17, 22, 'WA'),
(9, 20, 23, 'PG'),
(9, 19, 24, 'WA');

-- --------------------------------------------------------

--
-- Struktura tabulky `skupinazaku`
--

DROP TABLE IF EXISTS `skupinazaku`;
CREATE TABLE IF NOT EXISTS `skupinazaku` (
  `trida_nazev` varchar(10) COLLATE utf16_czech_ci NOT NULL,
  `idSkupiny` int(11) NOT NULL,
  `cisloSkupiny` int(11) NOT NULL,
  PRIMARY KEY (`idSkupiny`),
  UNIQUE KEY `cisloSkupiny_UNIQUE` (`idSkupiny`),
  KEY `fk_skupina_trida1_idx` (`trida_nazev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `skupinazaku`
--

INSERT INTO `skupinazaku` (`trida_nazev`, `idSkupiny`, `cisloSkupiny`) VALUES
('1A', 1, 0),
('1A', 2, 1),
('1A', 3, 2),
('2B', 4, 0),
('2B', 5, 1),
('2B', 6, 2),
('3C', 7, 0),
('3C', 8, 1),
('3C', 9, 2);

-- --------------------------------------------------------

--
-- Struktura tabulky `skupinazaku_has_zak`
--

DROP TABLE IF EXISTS `skupinazaku_has_zak`;
CREATE TABLE IF NOT EXISTS `skupinazaku_has_zak` (
  `skupinaZaku_idSkupiny` int(11) NOT NULL,
  `zak_ucet_idLogin` int(11) NOT NULL,
  PRIMARY KEY (`skupinaZaku_idSkupiny`,`zak_ucet_idLogin`),
  KEY `fk_skupinaZaku_has_zak_zak1_idx` (`zak_ucet_idLogin`),
  KEY `fk_skupinaZaku_has_zak_skupinaZaku1_idx` (`skupinaZaku_idSkupiny`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `skupinazaku_has_zak`
--

INSERT INTO `skupinazaku_has_zak` (`skupinaZaku_idSkupiny`, `zak_ucet_idLogin`) VALUES
(1, 1),
(2, 1),
(4, 2),
(5, 2),
(7, 3),
(8, 3),
(1, 4),
(2, 4),
(4, 5),
(5, 5),
(7, 6),
(8, 6),
(1, 7),
(3, 7),
(4, 8),
(6, 8),
(7, 9),
(9, 9),
(1, 10),
(3, 10),
(4, 11),
(6, 11),
(7, 12),
(9, 12),
(1, 13),
(3, 13),
(4, 14),
(6, 14),
(7, 15),
(9, 15);

-- --------------------------------------------------------

--
-- Struktura tabulky `trida`
--

DROP TABLE IF EXISTS `trida`;
CREATE TABLE IF NOT EXISTS `trida` (
  `nazev` varchar(10) COLLATE utf16_czech_ci NOT NULL,
  `ucitel_ucet_idLogin` int(11) NOT NULL,
  PRIMARY KEY (`nazev`),
  UNIQUE KEY `nazev_UNIQUE` (`nazev`),
  KEY `fk_trida_ucitel1_idx` (`ucitel_ucet_idLogin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `trida`
--

INSERT INTO `trida` (`nazev`, `ucitel_ucet_idLogin`) VALUES
('1A', 16),
('2B', 17),
('3C', 20);

-- --------------------------------------------------------

--
-- Struktura tabulky `ucet`
--

DROP TABLE IF EXISTS `ucet`;
CREATE TABLE IF NOT EXISTS `ucet` (
  `idLogin` int(11) NOT NULL,
  `login` varchar(45) COLLATE utf16_czech_ci NOT NULL,
  `heslo` varchar(200) COLLATE utf16_czech_ci NOT NULL,
  `jmeno` varchar(45) COLLATE utf16_czech_ci NOT NULL,
  `prijmeni` varchar(45) COLLATE utf16_czech_ci NOT NULL,
  PRIMARY KEY (`idLogin`),
  UNIQUE KEY `login_UNIQUE` (`login`),
  UNIQUE KEY `idLogin_UNIQUE` (`idLogin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `ucet`
--

INSERT INTO `ucet` (`idLogin`, `login`, `heslo`, `jmeno`, `prijmeni`) VALUES
(1, 'log1', 'ddfe9964a5f8d6f74d35dbde9b3a54f1735bc8b8', 'Štěpán', 'Mikuš'),
(2, 'log2', 'ddfe9964a5f8d6f74d35dbde9b3a54f1735bc8b8', 'Ivo', 'Brabenec'),
(3, 'log3', 'ddfe9964a5f8d6f74d35dbde9b3a54f1735bc8b8', 'Vít', 'Starý'),
(4, 'log4', 'ddfe9964a5f8d6f74d35dbde9b3a54f1735bc8b8', 'Rudolf', 'Mlejnek'),
(5, 'log5', 'ddfe9964a5f8d6f74d35dbde9b3a54f1735bc8b8', 'Václav', 'Rak'),
(6, 'log6', '54baf26ab4fb168e7bb1903bc894f9f7e67c2666', 'Felix', 'Běhal'),
(7, 'log7', '54baf26ab4fb168e7bb1903bc894f9f7e67c2666', 'Luděk', 'Valach'),
(8, 'log8', '54baf26ab4fb168e7bb1903bc894f9f7e67c2666', 'Vít', 'Stratil'),
(9, 'log9', '54baf26ab4fb168e7bb1903bc894f9f7e67c2666', 'Viktor', 'Kuna'),
(10, 'log10', '54baf26ab4fb168e7bb1903bc894f9f7e67c2666', 'Radan', 'Veselý'),
(11, 'log11', 'fb2846acb0f12a3d99b7cb271837446ef9d982d6', 'Alice', 'Šimáková'),
(12, 'log12', 'fb2846acb0f12a3d99b7cb271837446ef9d982d6', 'Kamila', 'Šmídlová'),
(13, 'log13', 'fb2846acb0f12a3d99b7cb271837446ef9d982d6', 'Hana', 'Havrdová'),
(14, 'log14', 'fb2846acb0f12a3d99b7cb271837446ef9d982d6', 'Natálie', 'Pavlíčková'),
(15, 'log15', 'fb2846acb0f12a3d99b7cb271837446ef9d982d6', 'Pavla', 'Sikorová'),
(16, 'log16', '6435c4c52c07c10030a742da46f16a12fbba34eb', 'František', 'Čížek'),
(17, 'log17', '6435c4c52c07c10030a742da46f16a12fbba34eb', 'Mikuláš', 'Svozil'),
(18, 'log18', '6435c4c52c07c10030a742da46f16a12fbba34eb', 'Filip', 'Fencl'),
(19, 'log19', '6435c4c52c07c10030a742da46f16a12fbba34eb', 'Andrea', 'Mášová'),
(20, 'log20', '6435c4c52c07c10030a742da46f16a12fbba34eb', 'Eva', 'Závodná');

-- --------------------------------------------------------

--
-- Struktura tabulky `ucitel`
--

DROP TABLE IF EXISTS `ucitel`;
CREATE TABLE IF NOT EXISTS `ucitel` (
  `ucet_idLogin` int(11) NOT NULL,
  `role` varchar(10) COLLATE utf16_czech_ci DEFAULT NULL,
  PRIMARY KEY (`ucet_idLogin`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `ucitel`
--

INSERT INTO `ucitel` (`ucet_idLogin`, `role`) VALUES
(16, 'zadna'),
(17, 'zadna'),
(18, 'zadna'),
(19, 'zadna'),
(20, 'zadna');

-- --------------------------------------------------------

--
-- Struktura tabulky `zak`
--

DROP TABLE IF EXISTS `zak`;
CREATE TABLE IF NOT EXISTS `zak` (
  `ucet_idLogin` int(11) NOT NULL,
  `trida_nazev` varchar(10) COLLATE utf16_czech_ci NOT NULL,
  PRIMARY KEY (`ucet_idLogin`),
  KEY `fk_zak_trida1_idx` (`trida_nazev`)
) ENGINE=InnoDB DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `zak`
--

INSERT INTO `zak` (`ucet_idLogin`, `trida_nazev`) VALUES
(1, '1A'),
(4, '1A'),
(7, '1A'),
(10, '1A'),
(13, '1A'),
(2, '2B'),
(5, '2B'),
(8, '2B'),
(11, '2B'),
(14, '2B'),
(3, '3C'),
(6, '3C'),
(9, '3C'),
(12, '3C'),
(15, '3C');

-- --------------------------------------------------------

--
-- Struktura tabulky `znamka`
--

DROP TABLE IF EXISTS `znamka`;
CREATE TABLE IF NOT EXISTS `znamka` (
  `idZnamka` int(11) NOT NULL AUTO_INCREMENT,
  `znamka` varchar(1) COLLATE utf16_czech_ci NOT NULL,
  `datum` date NOT NULL,
  `zak_ucet_idLogin` int(11) NOT NULL,
  `kategorieZnamek_idKategorieZnamek` int(11) NOT NULL,
  `predmetTridy_idpredmetTridy` int(11) NOT NULL,
  `poznamka` varchar(45) COLLATE utf16_czech_ci DEFAULT NULL,
  PRIMARY KEY (`idZnamka`),
  UNIQUE KEY `idZnamka_UNIQUE` (`idZnamka`),
  KEY `fk_znamka_zak1_idx` (`zak_ucet_idLogin`),
  KEY `fk_znamka_kategorieZnamek1_idx` (`kategorieZnamek_idKategorieZnamek`),
  KEY `fk_znamka_predmetTridy1_idx` (`predmetTridy_idpredmetTridy`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;

--
-- Vypisuji data pro tabulku `znamka`
--


--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `kategorieznamek`
--
ALTER TABLE `kategorieznamek`
  ADD CONSTRAINT `fk_kategorieZnamek_predmetTridy1` FOREIGN KEY (`predmetTridy_idpredmetTridy`) REFERENCES `predmettridy` (`idpredmetTridy`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_kategorieZnamek_ucitel1` FOREIGN KEY (`ucitel_ucet_idLogin`) REFERENCES `ucitel` (`ucet_idLogin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `predmettridy`
--
ALTER TABLE `predmettridy`
  ADD CONSTRAINT `fk_predmetTridy_predmet1` FOREIGN KEY (`predmet_zkratka`) REFERENCES `predmet` (`zkratka`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_predmetTridy_skupinaZaku1` FOREIGN KEY (`skupinaZaku_idSkupiny`) REFERENCES `skupinazaku` (`idSkupiny`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_predmetTridy_ucitel1` FOREIGN KEY (`ucitel_ucet_idLogin`) REFERENCES `ucitel` (`ucet_idLogin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `skupinazaku`
--
ALTER TABLE `skupinazaku`
  ADD CONSTRAINT `fk_skupina_trida1` FOREIGN KEY (`trida_nazev`) REFERENCES `trida` (`nazev`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `skupinazaku_has_zak`
--
ALTER TABLE `skupinazaku_has_zak`
  ADD CONSTRAINT `fk_skupinaZaku_has_zak_skupinaZaku1` FOREIGN KEY (`skupinaZaku_idSkupiny`) REFERENCES `skupinazaku` (`idSkupiny`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_skupinaZaku_has_zak_zak1` FOREIGN KEY (`zak_ucet_idLogin`) REFERENCES `zak` (`ucet_idLogin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `trida`
--
ALTER TABLE `trida`
  ADD CONSTRAINT `fk_trida_ucitel1` FOREIGN KEY (`ucitel_ucet_idLogin`) REFERENCES `ucitel` (`ucet_idLogin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `ucitel`
--
ALTER TABLE `ucitel`
  ADD CONSTRAINT `fk_ucitel_ucet` FOREIGN KEY (`ucet_idLogin`) REFERENCES `ucet` (`idLogin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `zak`
--
ALTER TABLE `zak`
  ADD CONSTRAINT `fk_zak_trida1` FOREIGN KEY (`trida_nazev`) REFERENCES `trida` (`nazev`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_zak_ucet1` FOREIGN KEY (`ucet_idLogin`) REFERENCES `ucet` (`idLogin`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Omezení pro tabulku `znamka`
--
ALTER TABLE `znamka`
  ADD CONSTRAINT `fk_znamka_kategorieZnamek1` FOREIGN KEY (`kategorieZnamek_idKategorieZnamek`) REFERENCES `kategorieznamek` (`idKategorieZnamek`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_znamka_predmetTridy1` FOREIGN KEY (`predmetTridy_idpredmetTridy`) REFERENCES `predmettridy` (`idpredmetTridy`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_znamka_zak1` FOREIGN KEY (`zak_ucet_idLogin`) REFERENCES `zak` (`ucet_idLogin`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
