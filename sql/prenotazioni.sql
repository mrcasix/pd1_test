CREATE TABLE IF NOT EXISTS `prenotazioni` (
  `id_prenotazione` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_utente` int(11) NOT NULL,
  `durata_assegnata` int(11) NOT NULL,
  `durata_richiesta` int(11) NOT NULL,
  `fk_id_orario_prenotazione` int(11) NOT NULL,
  PRIMARY KEY (`id_prenotazione`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dump dei dati per la tabella `prenotazioni`
--

INSERT INTO `prenotazioni` (`id_prenotazione`, `fk_id_utente`, `durata_assegnata`, `durata_richiesta`, `fk_id_orario_prenotazione`) VALUES
(2, 2, 40, 0, 1),
(3, 1, 12, 0, 10),
(4, 1, 12, 0, 10),
(5, 1, 12, 0, 10),
(6, 1, 124, 0, 10),
(7, 1, 34, 0, 10),
(12, 1, 12, 0, 1);

