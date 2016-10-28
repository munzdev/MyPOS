-- -----------------------------------------------------
-- Mandatory Data required by MyPOS
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (1, 'Manager');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (2, 'Bestellung Übersicht');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (4, 'Ausgabe');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (8, 'Ausgabe Vorschau');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (16, 'Bestellung hinzufügen');

COMMIT;

INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, 'Normal', 1);

COMMIT;