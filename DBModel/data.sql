-- -----------------------------------------------------
-- Mandatory Data required by MyPOS
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (1, 'Manager');
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (2, 'Bestellung Übersicht');
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (4, 'Ausgabe');
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (8, 'Ausgabe Vorschau');
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (16, 'Bestellung hinzufügen');

INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, 'Normal', 1);

COMMIT;