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
-- Data for table `tables`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `tables` (`tableid`, `name`, `data`) VALUES (DEFAULT, 'A1', NULL);
INSERT INTO `tables` (`tableid`, `name`, `data`) VALUES (DEFAULT, 'B32', NULL);
INSERT INTO `tables` (`tableid`, `name`, `data`) VALUES (DEFAULT, 'C32', NULL);
INSERT INTO `tables` (`tableid`, `name`, `data`) VALUES (DEFAULT, 'A4', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `events`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `events` (`eventid`, `name`, `date`, `active`) VALUES (DEFAULT, 'Frühschoppen', '01.09.2016', true);

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
INSERT INTO `menu_types` (`menu_typeid`, `name`, `tax`, `allowMixing`) VALUES (DEFAULT, 'Essen', 20, false);
INSERT INTO `menu_types` (`menu_typeid`, `name`, `tax`, `allowMixing`) VALUES (DEFAULT, 'Trinken', 10, true);

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
-- Data for table `menues`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 1, 'Wiener Schnitzel', 7.50, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 1, 'Schweinsbraten', 9, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 1, 'Pommes', 4, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 1, 'Bratwürstel', 5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 1, 'Gemüselaibchen', 6.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 2, 'Gemischter Salat', 2.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 2, 'Kartoffelsalat', 2.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 3, 'Cola', 2.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 3, 'Sprite', 2.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 3, 'Fanta', 2.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 3, 'Cola-Mix', 2.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 3, 'Mineral', 1.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 3, 'Wasser', 1, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 4, 'Märzen', 2.8, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 4, 'Ratsherrn', 2.8, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 4, 'Radler', 2.5, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 5, 'Rot', 2.6, 'AVAILABLE', NULL);
INSERT INTO `menues` (`menuid`, `eventid`, `menu_groupid`, `name`, `price`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 5, 'Weis', 2.6, 'AVAILABLE', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `orders_details`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orders_details` (`orders_detailid`, `menuid`, `orderid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`) VALUES (DEFAULT, 1, 1, 2, 7.50, NULL, NULL, NULL);
INSERT INTO `orders_details` (`orders_detailid`, `menuid`, `orderid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`) VALUES (DEFAULT, 2, 1, 1, 9, NULL, NULL, NULL);
INSERT INTO `orders_details` (`orders_detailid`, `menuid`, `orderid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`) VALUES (DEFAULT, 8, 2, 3, 2.5, NULL, NULL, NULL);
INSERT INTO `orders_details` (`orders_detailid`, `menuid`, `orderid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`) VALUES (DEFAULT, 14, 2, 2, 2.80, NULL, NULL, NULL);
INSERT INTO `orders_details` (`orders_detailid`, `menuid`, `orderid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`) VALUES (DEFAULT, 8, 3, 1, 4, NULL, NULL, NULL);
INSERT INTO `orders_details` (`orders_detailid`, `menuid`, `orderid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`) VALUES (DEFAULT, 1, 4, 2, 7.50, NULL, NULL, NULL);
INSERT INTO `orders_details` (`orders_detailid`, `menuid`, `orderid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `finished`) VALUES (DEFAULT, 2, 4, 1, 6.50, NULL, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_extras`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Pommes', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Reis', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Kartoffel', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Salat', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Knödel', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Pommes', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Reis', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Kartoffel', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Salat', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Zitrone', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Kraut', 'AVAILABLE', NULL);
INSERT INTO `menu_extras` (`menu_extraid`, `eventid`, `name`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Kraut', 'AVAILABLE', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_sizes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, '0,2L', 0.2);
INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, '0,25L', 0.25);
INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, '0,5L', 0.5);
INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, '0,33L', 0.33);
INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, 'Klein', 1);
INSERT INTO `menu_sizes` (`menu_sizeid`, `name`, `factor`) VALUES (DEFAULT, 'Groß', 1);

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
-- Data for table `orders_detail_sizes`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orders_detail_sizes` (`orders_detailid`, `menues_possible_sizeid`) VALUES (3, 12);
INSERT INTO `orders_detail_sizes` (`orders_detailid`, `menues_possible_sizeid`) VALUES (4, 24);
INSERT INTO `orders_detail_sizes` (`orders_detailid`, `menues_possible_sizeid`) VALUES (5, 13);
INSERT INTO `orders_detail_sizes` (`orders_detailid`, `menues_possible_sizeid`) VALUES (1, 1);
INSERT INTO `orders_detail_sizes` (`orders_detailid`, `menues_possible_sizeid`) VALUES (2, 3);
INSERT INTO `orders_detail_sizes` (`orders_detailid`, `menues_possible_sizeid`) VALUES (6, 1);
INSERT INTO `orders_detail_sizes` (`orders_detailid`, `menues_possible_sizeid`) VALUES (7, 3);

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
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 1, 7, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 2, 2, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 3, 4, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 4, 4, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 5, 1, DEFAULT);
INSERT INTO `events_user` (`events_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 6, 7, DEFAULT);

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
-- Data for table `orders_details_special_extra`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `orders_details_special_extra` (`orders_details_special_extraid`, `orderid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `verified`, `finished`, `availability`, `availability_amount`) VALUES (DEFAULT, 1, NULL, 1, NULL, NULL, 'Schnitzel ohne allem mit Schweinsbratten', 0, NULL, NULL, NULL);

COMMIT;

