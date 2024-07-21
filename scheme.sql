-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Mag 28, 2024 alle 11:35
-- Versione del server: 10.11.6-MariaDB-0+deb12u1
-- Versione PHP: 8.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `5i2_Mariucci`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `stato`
--

CREATE TABLE `stato` (
  `id` int(11) NOT NULL,
  `titolo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `stato`
--

INSERT INTO `stato` (`id`, `titolo`, `descripcion`) VALUES
(1, 'Nuovo', 'Attività appena creata e non ancora assegnata.'),
(2, 'In Lavorazione', 'Attività in fase di svolgimento.'),
(3, 'Completato', 'Attività completata e pronta per la revisione.'),
(4, 'Archiviato', 'Attività archiviata e completata.');

-- --------------------------------------------------------

--
-- Struttura della tabella `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `fk_creatore` int(11) DEFAULT NULL,
  `data_creazione` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `task`
--

INSERT INTO `task` (`id`, `fk_creatore`, `data_creazione`) VALUES
(30, 1, '2024-05-28'),
(31, 1, '2024-05-28'),
(32, 1, '2024-05-28');

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `id` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `pw` varchar(255) DEFAULT NULL,
  `ruolo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`id`, `username`, `pw`, `ruolo`) VALUES
(1, 'mario123', 'password123', 'amministratore'),
(2, 'lucia456', 'securePwd789', 'utente_normale'),
(3, 'giovanni789', 'superS3cret!', 'utente_normale');

-- --------------------------------------------------------

--
-- Struttura della tabella `versione`
--

CREATE TABLE `versione` (
  `id` int(11) NOT NULL,
  `titolo` varchar(255) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `priorita` int(11) DEFAULT NULL,
  `data_ver` date DEFAULT NULL,
  `fk_stato` int(11) DEFAULT NULL,
  `fk_utente` int(11) DEFAULT NULL,
  `fk_task` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `stato`
--
ALTER TABLE `stato`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_creatore` (`fk_creatore`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `versione`
--
ALTER TABLE `versione`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stato` (`fk_stato`),
  ADD KEY `fk_utente` (`fk_utente`),
  ADD KEY `fk_task` (`fk_task`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `stato`
--
ALTER TABLE `stato`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT per la tabella `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT per la tabella `versione`
--
ALTER TABLE `versione`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `task_ibfk_1` FOREIGN KEY (`fk_creatore`) REFERENCES `utente` (`id`);

--
-- Limiti per la tabella `versione`
--
ALTER TABLE `versione`
  ADD CONSTRAINT `versione_ibfk_1` FOREIGN KEY (`fk_stato`) REFERENCES `stato` (`id`),
  ADD CONSTRAINT `versione_ibfk_2` FOREIGN KEY (`fk_utente`) REFERENCES `utente` (`id`),
  ADD CONSTRAINT `versione_ibfk_3` FOREIGN KEY (`fk_task`) REFERENCES `task` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
