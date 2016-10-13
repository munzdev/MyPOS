-- -----------------------------------------------------
-- Data for table `users`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'admin', MD5('password'), 'Administrator', 'Cool', NULL, 1, '0664 / 1234567', NULL, true);
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'kellner', MD5('password'), 'Kellner', 'Schnell', NULL, 1, '0664 / 7654321', NULL, false);
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'essen', MD5('password'), 'Ausgabe', 'Essen', NULL, 1, '0664 5555555', NULL, false);
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'trinken1-1', MD5('password'), 'Ausgabe', 'Trinken 1-1', NULL, 1, '0664 1111111', NULL, false);
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'manager', MD5('password'), 'Manager', 'Nur', NULL, 1, '0664 8888888', NULL, false);
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'managerAll', MD5('password'), 'Manager', 'Alles', NULL, 1, '0664 9999999', NULL, false);
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'trinken2', MD5('password'), 'Ausgabe', 'Trinken 2', NULL, 1, '0664 2222222', NULL, false);
INSERT INTO `users` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'trinken1-2', MD5('password'), 'Ausgabe', 'Trinken 1-2', NULL, 1, '0664 3333333', NULL, false);

COMMIT;


-- -----------------------------------------------------
-- Data for table `events`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `events` (`eventid`, `name`, `date`, `active`) VALUES (DEFAULT, 'Frühschoppen', '2016-01-01 00:00:00', true);

COMMIT;


-- -----------------------------------------------------
-- Data for table `events_tables`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `events_tables` (`events_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'A1', NULL);
INSERT INTO `events_tables` (`events_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'B32', NULL);
INSERT INTO `events_tables` (`events_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'C32', NULL);
INSERT INTO `events_tables` (`events_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'A4', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `orders`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orders` (`orderid`, `eventid`, `tableid`, `userid`, `ordertime`, `priority`, `finished`) VALUES (DEFAULT, 1, 1, 1, NOW(), 1, NULL);
INSERT INTO `orders` (`orderid`, `eventid`, `tableid`, `userid`, `ordertime`, `priority`, `finished`) VALUES (DEFAULT, 1, 3, 1, NOW(), 2, NULL);
INSERT INTO `orders` (`orderid`, `eventid`, `tableid`, `userid`, `ordertime`, `priority`, `finished`) VALUES (DEFAULT, 1, 2, 1, NOW(), 3, NULL);
INSERT INTO `orders` (`orderid`, `eventid`, `tableid`, `userid`, `ordertime`, `priority`, `finished`) VALUES (DEFAULT, 1, 1, 1, NOW(), 4, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_types`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_types` (`menu_typeid`, `eventid`, `name`, `tax`, `allowMixing`) VALUES (DEFAULT, 1, 'Essen', 20, false);
INSERT INTO `menu_types` (`menu_typeid`, `eventid`, `name`, `tax`, `allowMixing`) VALUES (DEFAULT, 1, 'Trinken', 10, true);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_groupes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_groupes` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 1, 'Hauptspeisen');
INSERT INTO `menu_groupes` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 1, 'Beilagen');
INSERT INTO `menu_groupes` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 2, 'Antigetränke');
INSERT INTO `menu_groupes` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 2, 'Bier');
INSERT INTO `menu_groupes` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 2, 'Wein');

COMMIT;


-- -----------------------------------------------------
-- Data for table `availabilitys`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `availabilitys` (`availabilityid`, `name`) VALUES (DEFAULT, 'AVAILABLE');
INSERT INTO `availabilitys` (`availabilityid`, `name`) VALUES (DEFAULT, 'DELAYED');
INSERT INTO `availabilitys` (`availabilityid`, `name`) VALUES (DEFAULT, 'OUT OF ORDER');

COMMIT;


-- -----------------------------------------------------
-- Data for table `menues`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Wiener Schnitzel', 7.50, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Schweinsbraten', 9, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Pommes', 4, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Bratwürstel', 5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Gemüselaibchen', 6.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 2, 'Gemischter Salat', 2.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 2, 'Kartoffelsalat', 2.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Cola', 2.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Sprite', 2.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Fanta', 2.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Cola-Mix', 2.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Mineral', 1.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Wasser', 1, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 4, 'Märzen', 2.8, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 4, 'Ratsherrn', 2.8, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 4, 'Radler', 2.5, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 5, 'Rot', 2.6, 1, NULL);
INSERT INTO `menues` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 5, 'Weis', 2.6, 1, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_sizes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_sizes` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,2L', 0.2);
INSERT INTO `menu_sizes` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,25L', 0.25);
INSERT INTO `menu_sizes` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,5L', 0.5);
INSERT INTO `menu_sizes` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,33L', 0.33);
INSERT INTO `menu_sizes` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, 'Klein', 1);
INSERT INTO `menu_sizes` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, 'Groß', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `orders_details`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orders_details` (`orders_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`, `availabilityid`, `availability_amount`, `verified`) VALUES (DEFAULT, 1, 1, NULL, NULL, 2, 7.50, NULL, NULL, NULL, NULL, NULL, DEFAULT);
INSERT INTO `orders_details` (`orders_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`, `availabilityid`, `availability_amount`, `verified`) VALUES (DEFAULT, 1, 2, NULL, NULL, 1, 9, NULL, NULL, NULL, NULL, NULL, DEFAULT);
INSERT INTO `orders_details` (`orders_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`, `availabilityid`, `availability_amount`, `verified`) VALUES (DEFAULT, 2, 8, NULL, NULL, 3, 2.5, NULL, NULL, NULL, NULL, NULL, DEFAULT);
INSERT INTO `orders_details` (`orders_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`, `availabilityid`, `availability_amount`, `verified`) VALUES (DEFAULT, 2, 14, NULL, NULL, 2, 2.80, NULL, NULL, NULL, NULL, NULL, DEFAULT);
INSERT INTO `orders_details` (`orders_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`, `availabilityid`, `availability_amount`, `verified`) VALUES (DEFAULT, 3, 8, NULL, NULL, 1, 4, NULL, NULL, NULL, NULL, NULL, DEFAULT);
INSERT INTO `orders_details` (`orders_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`, `availabilityid`, `availability_amount`, `verified`) VALUES (DEFAULT, 4, 1, NULL, NULL, 2, 7.50, NULL, NULL, NULL, NULL, NULL, DEFAULT);
INSERT INTO `orders_details` (`orders_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`, `availabilityid`, `availability_amount`, `verified`) VALUES (DEFAULT, 4, 2, NULL, NULL, 1, 6.50, NULL, NULL, NULL, NULL, NULL, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_extras`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Pommes', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Reis', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Kartoffel', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Salat', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Knödel', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Pommes', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Reis', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Kartoffel', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Salat', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Zitrone', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Kraut', 1, NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Kraut', 1, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menues_possible_sizes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 1, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 1, -1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 2, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 2, -1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 3, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 3, -1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 4, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 4, -1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 5, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 5, -1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 7, 1, 1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 8, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 8, 2.5);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 9, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 9, 1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 10, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 10, 1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 11, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 11, 1);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 12, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 12, 0.8);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 13, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 13, 0.5);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 14, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 5, 14, 2);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 15, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 5, 15, 2);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 16, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 5, 16, 2);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 2, 17, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 2, 18, 1.5);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 17, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 18, 1.5);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 6, 0);
INSERT INTO `menues_possible_sizes` (`menues_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 7, 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menues_possible_extras`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 2, 1, 0);
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 3, 1, 0);
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 8, 2, 0);
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 11, 2, 0);
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 5, 3, 1);
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 11, 4, DEFAULT);
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 4, 4, 1.5);
INSERT INTO `menues_possible_extras` (`menues_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 5, 5, -0.5);

COMMIT;


-- -----------------------------------------------------
-- Data for table `orders_detail_extras`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orders_detail_extras` (`orders_detailid`, `menues_possible_extraid`) VALUES (1, 1);
INSERT INTO `orders_detail_extras` (`orders_detailid`, `menues_possible_extraid`) VALUES (1, 2);
INSERT INTO `orders_detail_extras` (`orders_detailid`, `menues_possible_extraid`) VALUES (6, 1);
INSERT INTO `orders_detail_extras` (`orders_detailid`, `menues_possible_extraid`) VALUES (6, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `orders_details_mixed_with`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orders_details_mixed_with` (`orders_detailid`, `menuid`) VALUES (5, 12);

COMMIT;


-- -----------------------------------------------------
-- Data for table `events_user`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 1, 31, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 2, 18, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 3, 4, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 4, 4, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 5, 1, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 6, 31, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 7, 4, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 8, 4, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distributions_places`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distributions_places` (`distributions_placeid`, `eventid`, `name`) VALUES (DEFAULT, 1, 'Getränke Ausgabe 1');
INSERT INTO `distributions_places` (`distributions_placeid`, `eventid`, `name`) VALUES (DEFAULT, 1, 'Getränke Ausgabe 2');
INSERT INTO `distributions_places` (`distributions_placeid`, `eventid`, `name`) VALUES (DEFAULT, 1, 'Essens Ausgabe');

COMMIT;


-- -----------------------------------------------------
-- Data for table `events_printers`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `events_printers` (`events_printerid`, `eventid`, `name`, `ip`, `port`, `default`, `characters_per_row`) VALUES (DEFAULT, 1, 'Default', '192.168.0.50', 9100, 1, 48);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distributions_places_groupes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distributions_places_groupes` (`distributions_placeid`, `menu_groupid`) VALUES (3, 1);
INSERT INTO `distributions_places_groupes` (`distributions_placeid`, `menu_groupid`) VALUES (3, 2);
INSERT INTO `distributions_places_groupes` (`distributions_placeid`, `menu_groupid`) VALUES (1, 3);
INSERT INTO `distributions_places_groupes` (`distributions_placeid`, `menu_groupid`) VALUES (1, 4);
INSERT INTO `distributions_places_groupes` (`distributions_placeid`, `menu_groupid`) VALUES (1, 5);
INSERT INTO `distributions_places_groupes` (`distributions_placeid`, `menu_groupid`) VALUES (2, 3);
INSERT INTO `distributions_places_groupes` (`distributions_placeid`, `menu_groupid`) VALUES (2, 4);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distributions_places_users`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distributions_places_users` (`distributions_placeid`, `userid`, `events_printerid`) VALUES (1, 4, 1);
INSERT INTO `distributions_places_users` (`distributions_placeid`, `userid`, `events_printerid`) VALUES (1, 8, 1);
INSERT INTO `distributions_places_users` (`distributions_placeid`, `userid`, `events_printerid`) VALUES (2, 7, 1);
INSERT INTO `distributions_places_users` (`distributions_placeid`, `userid`, `events_printerid`) VALUES (3, 3, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distributions_places_tables`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (1, 3, 1);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (2, 3, 1);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (3, 3, 1);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (4, 3, 1);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (1, 3, 2);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (2, 3, 2);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (3, 3, 2);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (4, 3, 2);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (1, 1, 5);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (2, 1, 5);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (3, 1, 5);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (4, 1, 5);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (1, 1, 3);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (2, 1, 3);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (1, 1, 4);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (2, 1, 4);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (3, 2, 3);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (4, 2, 3);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (3, 2, 4);
INSERT INTO `distributions_places_tables` (`tableid`, `distributions_placeid`, `menu_groupid`) VALUES (4, 2, 4);

COMMIT;


-- -----------------------------------------------------
-- Data for table `payment_types`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `payment_types` (`idpayment_typeid`, `name`) VALUES (DEFAULT, 'Cash');
INSERT INTO `payment_types` (`idpayment_typeid`, `name`) VALUES (DEFAULT, 'Creditcard');
INSERT INTO `payment_types` (`idpayment_typeid`, `name`) VALUES (DEFAULT, 'Debitcard');

COMMIT;

