-- -----------------------------------------------------
-- Mandatory Data required by MyPOS
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (1, 'Manager');
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (2, 'Kellner');
INSERT INTO `events_user_role` (`events_user_roleid`, `name`) VALUES (4, 'Ausgabe');

INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, 'Normal', 1);

COMMIT;