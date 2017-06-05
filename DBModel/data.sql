-- -----------------------------------------------------
-- Mandatory Data required by MyPOS
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (1, 'USER_ROLE_USERMESSAGE');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (2, 'USER_ROLE_ORDER_OVERVIEW');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (4, 'USER_ROLE_ORDER_ADD');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (8, 'USER_ROLE_ORDER_MODIFY');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (16, 'USER_ROLE_ORDER_MODIFY_PRICE');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (32, 'USER_ROLE_ORDER_MODIFY_PRIORITY');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (64, 'USER_ROLE_ORDER_CANCEL');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (128, 'USER_ROLE_INVOICE_OVERVIEW');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (256, 'USER_ROLE_INVOICE_ADD');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (512, 'USER_ROLE_INVOICE_CANCEL');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (1024, 'USER_ROLE_INVOICE_CUSTOMER_OVERVIEW');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (2048, 'USER_ROLE_INVOICE_CUSTOMER_ADD');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (4096, 'USER_ROLE_INVOICE_CUSTOMER_MODIFY');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (8192, 'USER_ROLE_INVOICE_CUSTOMER_REMOVE');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (16384, 'USER_ROLE_PAYMENT_OVERVIEW');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (32768, 'USER_ROLE_PAYMENT_ADD');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (65536, 'USER_ROLE_PAYMENT_CANCEL');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (131072, 'USER_ROLE_PAYMENT_COUPON_OVERVIEW');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (262144, 'USER_ROLE_PAYMENT_COUPON_ADD');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (524288, 'USER_ROLE_PAYMENT_COUPON_MODIFY');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (1048576, 'USER_ROLE_PAYMENT_COUPON_CANCEL');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (2097152, 'USER_ROLE_MANAGER_OVERVIEW');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (4194304, 'USER_ROLE_MANAGER_CALLBACK');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (8388608, 'USER_ROLE_MANAGER_CHECK_SPECIAL_ORDER');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (16777216, 'USER_ROLE_MANAGER_CHECK_NEW_TABLE');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (33554432, 'USER_ROLE_MANAGER_GROUPMESSAGE');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (67108864, 'USER_ROLE_MANAGER_SET_AVAILABILITY');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (134217728, 'USER_ROLE_MANAGER_STATISTIC');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (268435456, 'USER_ROLE_DISTRIBUTION_OVERVIEW');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (536870912, 'USER_ROLE_DISTRIBUTION_SET_AVAILABILITY');
INSERT INTO `user_role` (`user_roleid`, `name`) VALUES (1073741824, 'USER_ROLE_DISTRIBUTION_PREVIEW');

COMMIT;

-- -----------------------------------------------------
-- Data for table `invoice_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `invoice_type` (`invoice_typeid`, `name`) VALUES (DEFAULT, 'INVOICE');
INSERT INTO `invoice_type` (`invoice_typeid`, `name`) VALUES (DEFAULT, 'CANCELLATION');
INSERT INTO `invoice_type` (`invoice_typeid`, `name`) VALUES (DEFAULT, 'DAY_DOCUMENT');
INSERT INTO `invoice_type` (`invoice_typeid`, `name`) VALUES (DEFAULT, 'MONTH_DOCUMENT');
INSERT INTO `invoice_type` (`invoice_typeid`, `name`) VALUES (DEFAULT, 'TRAINING');

COMMIT;

-- -----------------------------------------------------
-- Data for table `payment_type`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `payment_type` (`payment_typeid`, `name`) VALUES (DEFAULT, 'CASH');
INSERT INTO `payment_type` (`payment_typeid`, `name`) VALUES (DEFAULT, 'BANK_TRANSFER');

COMMIT;

-- -----------------------------------------------------
-- Data for table `menu_size`
-- -----------------------------------------------------
START TRANSACTION;

INSERT INTO `menu_size` (`menu_sizeid`, `eventid`, `name`, `factor`, `is_deleted`) VALUES (DEFAULT, 1, 'Normal', 1, NULL);

COMMIT;

-- -----------------------------------------------------
-- Data for table `availability`
-- -----------------------------------------------------
START TRANSACTION;
INSERT INTO `availability` (`availabilityid`, `name`) VALUES (DEFAULT, 'AVAILABLE');
INSERT INTO `availability` (`availabilityid`, `name`) VALUES (DEFAULT, 'DELAYED');
INSERT INTO `availability` (`availabilityid`, `name`) VALUES (DEFAULT, 'OUT_OF_ORDER');

COMMIT;