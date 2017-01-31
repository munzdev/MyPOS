-- -----------------------------------------------------
-- Data for table `user`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'admin', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Administrator', 'Cool', NULL, 1, '0664 / 1234567', NULL, true);
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'kellner', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Kellner', 'Schnell', NULL, 1, '0664 / 7654321', NULL, false);
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'essen', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Ausgabe', 'Essen', NULL, 1, '0664 5555555', NULL, false);
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'trinken1-1', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Ausgabe', 'Trinken 1-1', NULL, 1, '0664 1111111', NULL, false);
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'manager', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Manager', 'Nur', NULL, 1, '0664 8888888', NULL, false);
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'managerAll', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Manager', 'Alles', NULL, 1, '0664 9999999', NULL, false);
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'trinken2', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Ausgabe', 'Trinken 2', NULL, 1, '0664 2222222', NULL, false);
INSERT INTO `user` (`userid`, `username`, `password`, `firstname`, `lastname`, `autologin_hash`, `active`, `phonenumber`, `call_request`, `is_admin`) VALUES (DEFAULT, 'trinken1-2', '$2y$10$og9U0.a3fLAalSfUwwCRl.IHfcLoX.E11GpoiOJ7ZhUuDHSaFCZHy', 'Ausgabe', 'Trinken 1-2', NULL, 1, '0664 3333333', NULL, false);

COMMIT;


-- -----------------------------------------------------
-- Data for table `event`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `event` (`eventid`, `name`, `date`, `active`) VALUES (DEFAULT, 'Frühschoppen', '2016-01-01 00:00:00', true);

COMMIT;


-- -----------------------------------------------------
-- Data for table `event_table`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `event_table` (`event_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'A1', NULL);
INSERT INTO `event_table` (`event_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'B32', NULL);
INSERT INTO `event_table` (`event_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'C32', NULL);
INSERT INTO `event_table` (`event_tableid`, `eventid`, `name`, `data`) VALUES (DEFAULT, 1, 'A4', NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `order`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `order` (`orderid`, `event_tableid`, `userid`, `ordertime`, `priority`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 1, 1, NOW(), 1, NULL, NULL);
INSERT INTO `order` (`orderid`, `event_tableid`, `userid`, `ordertime`, `priority`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 3, 1, NOW(), 2, NULL, NULL);
INSERT INTO `order` (`orderid`, `event_tableid`, `userid`, `ordertime`, `priority`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 2, 1, NOW(), 3, NULL, NULL);
INSERT INTO `order` (`orderid`, `event_tableid`, `userid`, `ordertime`, `priority`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 1, 1, NOW(), 4, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_type` (`menu_typeid`, `eventid`, `name`, `tax`, `allowMixing`) VALUES (DEFAULT, 1, 'Essen', 20, false);
INSERT INTO `menu_type` (`menu_typeid`, `eventid`, `name`, `tax`, `allowMixing`) VALUES (DEFAULT, 1, 'Trinken', 10, true);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_group`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_group` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 1, 'Hauptspeisen');
INSERT INTO `menu_group` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 1, 'Beilagen');
INSERT INTO `menu_group` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 2, 'Antigetränke');
INSERT INTO `menu_group` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 2, 'Bier');
INSERT INTO `menu_group` (`menu_groupid`, `menu_typeid`, `name`) VALUES (DEFAULT, 2, 'Wein');

COMMIT;


-- -----------------------------------------------------
-- Data for table `availability`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `availability` (`availabilityid`, `name`) VALUES (DEFAULT, 'AVAILABLE');
INSERT INTO `availability` (`availabilityid`, `name`) VALUES (DEFAULT, 'DELAYED');
INSERT INTO `availability` (`availabilityid`, `name`) VALUES (DEFAULT, 'OUT OF ORDER');

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Wiener Schnitzel', 7.50, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Schweinsbraten', 9, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Pommes', 4, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Bratwürstel', 5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'Gemüselaibchen', 6.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 2, 'Gemischter Salat', 2.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 2, 'Kartoffelsalat', 2.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Cola', 2.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Sprite', 2.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Fanta', 2.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Cola-Mix', 2.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Mineral', 1.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 3, 'Wasser', 1, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 4, 'Märzen', 2.8, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 4, 'Ratsherrn', 2.8, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 4, 'Radler', 2.5, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 5, 'Rot', 2.6, 1, NULL);
INSERT INTO `menu` (`menuid`, `menu_groupid`, `name`, `price`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 5, 'Weis', 2.6, 1, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_size`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,2L', 0.2);
INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,25L', 0.25);
INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,5L', 0.5);
INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, '0,33L', 0.33);
INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, 'Klein', 1);
INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`) VALUES (DEFAULT, 1, 'Groß', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `order_detail`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 1, 1, 1, NULL, 2, 7.50, NULL, NULL, 1, NULL, true, NULL, NULL);
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 1, 2, 1, NULL, 1, 9, NULL, NULL, 1, NULL, true, NULL, NULL);
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 2, 8, 3, NULL, 3, 2.5, NULL, NULL, 1, NULL, true, NULL, NULL);
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 2, 14, 5, NULL, 2, 2.80, NULL, NULL, 1, NULL, true, NULL, NULL);
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 3, 8, 2, NULL, 1, 4, NULL, NULL, 1, NULL, true, NULL, NULL);
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 4, 1, 1, NULL, 2, 7.50, NULL, NULL, 1, NULL, true, NULL, NULL);
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 4, 2, 1, NULL, 1, 6.50, NULL, NULL, 1, NULL, true, NULL, NULL);
INSERT INTO `order_detail` (`order_detailid`, `orderid`, `menuid`, `menu_sizeid`, `menu_groupid`, `amount`, `single_price`, `single_price_modified_by_userid`, `extra_detail`, `availabilityid`, `availability_amount`, `verified`, `distribution_finished`, `invoice_finished`) VALUES (DEFAULT, 1, NULL, NULL, NULL, 2, DEFAULT, NULL, 'Alles mit ohne bitte', 1, NULL, false, NULL, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_extra`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Pommes', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Reis', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Kartoffel', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Salat', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Knödel', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Pommes', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Reis', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Kartoffel', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Salat', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Zitrone', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'ohne Kraut', 1, NULL);
INSERT INTO `menu_extra` (`menu_extraid`, `eventid`, `name`, `availabilityid`, `availability_amount`) VALUES (DEFAULT, 1, 'mit Kraut', 1, NULL);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_possible_size`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 1, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 1, -1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 2, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 2, -1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 3, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 3, -1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 4, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 4, -1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 5, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 6, 5, -1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 7, 1, 1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 8, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 8, 2.5);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 9, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 9, 1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 10, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 10, 1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 11, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 11, 1);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 12, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 12, 0.8);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 13, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 13, 0.5);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 14, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 5, 14, 2);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 15, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 5, 15, 2);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 4, 16, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 5, 16, 2);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 2, 17, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 2, 18, 1.5);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 17, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 3, 18, 1.5);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 6, 0);
INSERT INTO `menu_possible_size` (`menu_possible_sizeid`, `menu_sizeid`, `menuid`, `price`) VALUES (DEFAULT, 1, 7, 0);

COMMIT;


-- -----------------------------------------------------
-- Data for table `menu_possible_extra`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 2, 1, 0);
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 3, 1, 0);
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 8, 2, 0);
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 11, 2, 0);
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 5, 3, 1);
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 11, 4, DEFAULT);
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 4, 4, 1.5);
INSERT INTO `menu_possible_extra` (`menu_possible_extraid`, `menu_extraid`, `menuid`, `price`) VALUES (DEFAULT, 5, 5, -0.5);

COMMIT;


-- -----------------------------------------------------
-- Data for table `order_detail_extra`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `order_detail_extra` (`order_detailid`, `menu_possible_extraid`) VALUES (1, 1);
INSERT INTO `order_detail_extra` (`order_detailid`, `menu_possible_extraid`) VALUES (1, 2);
INSERT INTO `order_detail_extra` (`order_detailid`, `menu_possible_extraid`) VALUES (6, 1);
INSERT INTO `order_detail_extra` (`order_detailid`, `menu_possible_extraid`) VALUES (6, 2);

COMMIT;


-- -----------------------------------------------------
-- Data for table `order_detail_mixed_with`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `order_detail_mixed_with` (`order_detailid`, `menuid`) VALUES (5, 12);

COMMIT;


-- -----------------------------------------------------
-- Data for table `event_user`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 1, 2147483647, DEFAULT);
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 2, 106319, DEFAULT);
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 3, 1879154479, DEFAULT);
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 4, 1879154479, DEFAULT);
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 5, 266338304, DEFAULT);
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 6, 268435455, DEFAULT);
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 7, 1879154479, DEFAULT);
INSERT INTO `event_user` (`event_userid`, `eventid`, `userid`, `user_roles`, `begin_money`) VALUES (DEFAULT, 1, 8, 1879154479, DEFAULT);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distribution_place`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distribution_place` (`distribution_placeid`, `eventid`, `name`) VALUES (DEFAULT, 1, 'Getränke Ausgabe 1');
INSERT INTO `distribution_place` (`distribution_placeid`, `eventid`, `name`) VALUES (DEFAULT, 1, 'Getränke Ausgabe 2');
INSERT INTO `distribution_place` (`distribution_placeid`, `eventid`, `name`) VALUES (DEFAULT, 1, 'Essens Ausgabe');

COMMIT;


-- -----------------------------------------------------
-- Data for table `event_printer`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `event_printer` (`event_printerid`, `eventid`, `name`, `type`, `attr1`, `attr2`, `default`, `characters_per_row`) VALUES (DEFAULT, 1, 'Default', 1, '192.168.0.50', '9100', 1, 48);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distribution_place_group`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distribution_place_group` (`distribution_place_groupid`, `distribution_placeid`, `menu_groupid`) VALUES (DEFAULT, 3, 1);
INSERT INTO `distribution_place_group` (`distribution_place_groupid`, `distribution_placeid`, `menu_groupid`) VALUES (DEFAULT, 3, 2);
INSERT INTO `distribution_place_group` (`distribution_place_groupid`, `distribution_placeid`, `menu_groupid`) VALUES (DEFAULT, 1, 3);
INSERT INTO `distribution_place_group` (`distribution_place_groupid`, `distribution_placeid`, `menu_groupid`) VALUES (DEFAULT, 1, 4);
INSERT INTO `distribution_place_group` (`distribution_place_groupid`, `distribution_placeid`, `menu_groupid`) VALUES (DEFAULT, 1, 5);
INSERT INTO `distribution_place_group` (`distribution_place_groupid`, `distribution_placeid`, `menu_groupid`) VALUES (DEFAULT, 2, 3);
INSERT INTO `distribution_place_group` (`distribution_place_groupid`, `distribution_placeid`, `menu_groupid`) VALUES (DEFAULT, 2, 4);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distribution_place_user`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distribution_place_user` (`distribution_placeid`, `userid`, `event_printerid`) VALUES (1, 4, 1);
INSERT INTO `distribution_place_user` (`distribution_placeid`, `userid`, `event_printerid`) VALUES (1, 8, 1);
INSERT INTO `distribution_place_user` (`distribution_placeid`, `userid`, `event_printerid`) VALUES (2, 7, 1);
INSERT INTO `distribution_place_user` (`distribution_placeid`, `userid`, `event_printerid`) VALUES (3, 3, 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `distribution_place_table`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (1, 1);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (2, 1);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (3, 1);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (4, 1);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (1, 2);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (2, 2);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (3, 2);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (4, 2);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (1, 5);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (2, 5);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (3, 5);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (4, 5);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (1, 3);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (2, 3);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (1, 4);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (2, 4);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (3, 6);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (4, 6);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (3, 7);
INSERT INTO `distribution_place_table` (`event_tableid`, `distribution_place_groupid`) VALUES (4, 7);

COMMIT;


-- -----------------------------------------------------
-- Data for table `event_contact`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `event_contact` (`event_contactid`, `eventid`, `title`, `name`, `contact_person`, `address`, `address2`, `city`, `zip`, `tax_identification_nr`, `telephon`, `fax`, `email`, `active`, `default`) VALUES (DEFAULT, 1, 'Herr', 'Max Mustermann', NULL, 'Strasse 1', NULL, 'Olstadt', '21123', '1213', NULL, NULL, 'customer@email.com', true, false);
INSERT INTO `event_contact` (`event_contactid`, `eventid`, `title`, `name`, `contact_person`, `address`, `address2`, `city`, `zip`, `tax_identification_nr`, `telephon`, `fax`, `email`, `active`, `default`) VALUES (DEFAULT, 1, 'Firma', 'Company Test', NULL, 'Street whatever 1', NULL, 'City', '1234', '141231', '0664/123456', NULL, 'company@test.at', true, true);

COMMIT;


-- -----------------------------------------------------
-- Data for table `event_bankinformation`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `event_bankinformation` (`event_bankinformationid`, `eventid`, `name`, `iban`, `bic`, `active`) VALUES (DEFAULT, 1, 'Test Bank Int.', 'AT32123456', 'ATOO123454', 1);

COMMIT;


-- -----------------------------------------------------
-- Data for table `coupon`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `coupon` (`couponid`, `eventid`, `created_by_userid`, `code`, `created`, `value`) VALUES (DEFAULT, 1, 1, '1234', 'NOW', 20);

COMMIT;