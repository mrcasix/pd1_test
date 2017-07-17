-- n SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generato il: 17 lug, 2017 at 02:48 AM
-- Versione MySQL: 5.1.37
-- Versione PHP: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `university_pd1`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `articoli`
--

CREATE TABLE IF NOT EXISTS `articoli` (
  `id_articolo` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `descrizione` text NOT NULL,
  `immagine` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_articolo`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `articoli`
--

INSERT INTO `articoli` (`id_articolo`, `nome`, `descrizione`, `immagine`) VALUES
(1, 'Articolo di prova', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur varius non est ut tincidunt. Duis quam magna, ullamcorper quis metus vitae, dignissim condimentum orci. Ut viverra nunc at enim consequat, in fermentum dui convallis. Pellentesque ante massa, egestas vel metus et, lobortis accumsan nulla. Donec eget pellentesque leo. Nunc a malesuada orci. Suspendisse viverra eros ex, egestas dignissim odio bibendum vitae. Nullam ac iaculis sem.', 'blank_image.png');

-- --------------------------------------------------------

--
-- Struttura della tabella `commenti`
--

CREATE TABLE IF NOT EXISTS `commenti` (
  `id_commento` int(11) NOT NULL AUTO_INCREMENT,
  `testo` text NOT NULL,
  `punteggio` int(11) NOT NULL DEFAULT '-1',
  `fk_id_utente` int(11) NOT NULL,
  `fk_id_articolo` int(11) NOT NULL,
  PRIMARY KEY (`id_commento`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `commenti`
--

INSERT INTO `commenti` (`id_commento`, `testo`, `punteggio`, `fk_id_utente`, `fk_id_articolo`) VALUES
(2, 'fa schifo', 3, 3, 1),
(3, 'fa ancora più schifo', 2, 2, 1),
(7, 'kjk', 0, 6, 1),
(8, 'Che cazzo di prodotto.\r\n\r\nBoh', 2, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `corsi`
--

CREATE TABLE IF NOT EXISTS `corsi` (
  `id_corso` int(11) NOT NULL AUTO_INCREMENT,
  `titolo` varchar(255) NOT NULL,
  PRIMARY KEY (`id_corso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dump dei dati per la tabella `corsi`
--


-- --------------------------------------------------------

--
-- Struttura della tabella `giudizi`
--

CREATE TABLE IF NOT EXISTS `giudizi` (
  `id_giudizio` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_utente` int(11) NOT NULL,
  `fk_id_commento` int(11) NOT NULL,
  `tipo` int(1) NOT NULL,
  PRIMARY KEY (`id_giudizio`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dump dei dati per la tabella `giudizi`
--

INSERT INTO `giudizi` (`id_giudizio`, `fk_id_utente`, `fk_id_commento`, `tipo`) VALUES
(1, 3, 2, 1),
(4, 3, 2, -1),
(3, 3, 2, 1),
(5, 2, 2, 1),
(6, 2, 2, 1),
(7, 2, 2, 1),
(16, 2, 3, 1),
(15, 2, 3, -1),
(14, 2, 3, 1),
(17, 1, 3, -1),
(18, 1, 3, -1),
(19, 1, 3, -1),
(23, 1, 2, -1),
(24, 1, 2, -1),
(25, 1, 2, -1),
(35, 1, 7, -1),
(36, 1, 7, 1),
(37, 1, 7, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `orario_prenotazioni`
--

CREATE TABLE IF NOT EXISTS `orario_prenotazioni` (
  `id_orario_prenotazioni` int(11) NOT NULL AUTO_INCREMENT,
  `ora_inizio` time NOT NULL,
  `ora_fine` time NOT NULL,
  PRIMARY KEY (`id_orario_prenotazioni`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `orario_prenotazioni`
--

INSERT INTO `orario_prenotazioni` (`id_orario_prenotazioni`, `ora_inizio`, `ora_fine`) VALUES
(1, '14:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Struttura della tabella `prenotazioni`
--

CREATE TABLE IF NOT EXISTS `prenotazioni` (
  `id_prenotazione` int(11) NOT NULL,
  `fk_id_utente` int(11) NOT NULL,
  `durata` int(11) NOT NULL,
  `fk_id_orario_prenotazione` int(11) NOT NULL,
  PRIMARY KEY (`id_prenotazione`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`id_prenotazione`, `fk_id_utente`, `durata`, `fk_id_orario_prenotazione`) VALUES
(1, 1, 10, 1),
(2, 2, 40, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE IF NOT EXISTS `utenti` (
  `id_utente` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `cognome` varchar(255) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_utente`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id_utente`, `nome`, `cognome`, `mail`, `password`) VALUES
(1, 'mario', 'rossi', 'u1@p.it', '189bbbb00c5f1fb7fba9ad9285f193d1'),
(2, 'Guido', 'Forte', 'u2@p.it', '1d665b9b1467944c128a5575119d1cfd'),
(3, 'Perla', 'Pace', 'u3@p.it', '7bc3ca68769437ce986455407dab2a1f'),
(4, 'rth', 'rth', 'rth', '189bbbb00c5f1fb7fba9ad9285f193d1'),
(5, 'kdlskf', 'kdlskf', 'kdlskf', '189bbbb00c5f1fb7fba9ad9285f193d1'),
(6, 'kdlskf', 'kdlskf', 'kdlskf', '189bbbb00c5f1fb7fba9ad9285f193d1'),
(7, 'eoriwp', 'eoriwp', 'eoriwp', 'f27f6f1c7c5cbf4e3e192e0a47b85300'),
(8, 'lòelgò', 'lòelgò', 'lòelgò', 'd41d8cd98f00b204e9800998ecf8427e');
"<?php echo __LINK_SITO__?>login.php"<?php echo __LINK_SITO__?>login.php""
