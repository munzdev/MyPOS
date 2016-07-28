-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mypos
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `users` ;

CREATE TABLE IF NOT EXISTS `users` (
  `userid` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `firstname` VARCHAR(64) NOT NULL,
  `lastname` VARCHAR(64) NOT NULL,
  `autologin_hash` VARCHAR(255) NULL,
  `active` TINYINT NOT NULL,
  `phonenumber` VARCHAR(45) NOT NULL,
  `call_request` DATETIME NULL,
  `is_admin` TINYINT(1) NULL,
  PRIMARY KEY (`userid`),
  UNIQUE INDEX `user_name` (`username` ASC),
  INDEX `active` (`active` ASC),
  UNIQUE INDEX `userid_UNIQUE` (`userid` ASC))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
COMMENT = 'Enthält alle Benutzer, die Zugriff auf die App haben';


-- -----------------------------------------------------
-- Table `tables`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `tables` ;

CREATE TABLE IF NOT EXISTS `tables` (
  `tableid` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `data` VARCHAR(255) NULL,
  UNIQUE INDEX `tableid_UNIQUE` (`tableid` ASC),
  INDEX `tables_name` (`name` ASC),
  PRIMARY KEY (`tableid`))
ENGINE = InnoDB
COMMENT = 'Enthält Tischnummer, die es gibt';


-- -----------------------------------------------------
-- Table `events`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events` ;

CREATE TABLE IF NOT EXISTS `events` (
  `eventid` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `date` DATETIME NOT NULL,
  `active` TINYINT(1) NOT NULL,
  PRIMARY KEY (`eventid`),
  UNIQUE INDEX `eventsid_UNIQUE` (`eventid` ASC),
  INDEX `events_active` (`active` ASC))
ENGINE = InnoDB
COMMENT = 'Enthält die verschiedene Events, bei dem das POS System verwendet wird';


-- -----------------------------------------------------
-- Table `orders`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders` ;

CREATE TABLE IF NOT EXISTS `orders` (
  `orderid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `tableid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  `ordertime` DATETIME NOT NULL,
  `priority` INT NOT NULL,
  `finished` DATETIME NULL,
  PRIMARY KEY (`orderid`, `eventid`, `tableid`, `userid`),
  UNIQUE INDEX `oder_id_UNIQUE` (`orderid` ASC),
  INDEX `ordertime` (`ordertime` ASC),
  INDEX `fk_orders_users1_idx` (`userid` ASC),
  INDEX `fk_orders_tables_idx` (`tableid` ASC),
  INDEX `fk_orders_events1_idx` (`eventid` ASC),
  INDEX `priority` (`priority` ASC),
  INDEX `finished` (`finished` ASC),
  CONSTRAINT `fk_orders_tables`
    FOREIGN KEY (`tableid`)
    REFERENCES `tables` (`tableid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `events` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält die Bestellungen die von einem Tisch gemacht wurden durch einen Benutzer';


-- -----------------------------------------------------
-- Table `menu_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_types` ;

CREATE TABLE IF NOT EXISTS `menu_types` (
  `menu_typeid` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  `tax` SMALLINT NOT NULL,
  `allowMixing` TINYINT(1) NOT NULL,
  PRIMARY KEY (`menu_typeid`),
  UNIQUE INDEX `menu_typeid_UNIQUE` (`menu_typeid` ASC))
ENGINE = InnoDB
COMMENT = 'Enthält die grundlegende Nahrungstypen ( Essen, Trinken, ...) und die dafür gesetzlichen Steuern';


-- -----------------------------------------------------
-- Table `menu_groupes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_groupes` ;

CREATE TABLE IF NOT EXISTS `menu_groupes` (
  `menu_groupid` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_typeid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`menu_groupid`, `menu_typeid`),
  UNIQUE INDEX `menu_groupid_UNIQUE` (`menu_groupid` ASC),
  INDEX `fk_menu_groupes_menu_types1_idx` (`menu_typeid` ASC),
  CONSTRAINT `fk_menu_groupes_menu_types1`
    FOREIGN KEY (`menu_typeid`)
    REFERENCES `menu_types` (`menu_typeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enhält die Untergruppen der Menükarte (Hauptspeise, Beilagen, Antigetränke, Biere, ... )';


-- -----------------------------------------------------
-- Table `menues`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menues` ;

CREATE TABLE IF NOT EXISTS `menues` (
  `menuid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `menu_groupid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `price` DECIMAL(7,2) NOT NULL,
  `availability` ENUM('AVAILABLE', 'DELAYED', 'OUT OF ORDER') NOT NULL,
  UNIQUE INDEX `menuid_UNIQUE` (`menuid` ASC),
  INDEX `fk_menues_menu_groupes1_idx` (`menu_groupid` ASC),
  PRIMARY KEY (`menuid`, `eventid`, `menu_groupid`),
  INDEX `fk_menues_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_menues_menu_groupes1`
    FOREIGN KEY (`menu_groupid`)
    REFERENCES `menu_groupes` (`menu_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menues_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `events` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enhält das Menü das Angeboten werden kann (Schnitzel, Schweinsbaraten, Cola, Sprite, Wasser, ...) mit dem Standartpreis';


-- -----------------------------------------------------
-- Table `orders_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_details` ;

CREATE TABLE IF NOT EXISTS `orders_details` (
  `orders_detailid` INT(11) NOT NULL AUTO_INCREMENT,
  `menuid` INT(11) NOT NULL,
  `orderid` INT(11) NOT NULL,
  `amount` TINYINT NOT NULL,
  `single_price` DECIMAL(7,2) NOT NULL,
  `single_price_modified_by_userid` INT(11) NULL,
  `extra_detail` VARCHAR(255) NULL,
  `finished` DATETIME NULL,
  PRIMARY KEY (`orders_detailid`, `menuid`, `orderid`),
  UNIQUE INDEX `orders_detailid_UNIQUE` (`orders_detailid` ASC),
  INDEX `fk_orders_details_menues1_idx` (`menuid` ASC),
  INDEX `fk_orders_details_orders1_idx` (`orderid` ASC),
  INDEX `fk_orders_details_users1_idx` (`single_price_modified_by_userid` ASC),
  INDEX `idx_amount` (`amount` ASC),
  CONSTRAINT `fk_orders_details_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menues` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_orders1`
    FOREIGN KEY (`orderid`)
    REFERENCES `orders` (`orderid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_users1`
    FOREIGN KEY (`single_price_modified_by_userid`)
    REFERENCES `users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält die genauen Bestell-Positionen einner Bestellung, die getätigte Zahlung dafür und wie oft diese Position bestellt wurde. menuid is leer(0), wenn es sich um einen \"extrawunsch\" handelt.';


-- -----------------------------------------------------
-- Table `menu_extras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_extras` ;

CREATE TABLE IF NOT EXISTS `menu_extras` (
  `menu_extraid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `availability` ENUM('AVAILABLE', 'DELAYED', 'OUT OF ORDER') NOT NULL,
  PRIMARY KEY (`menu_extraid`, `eventid`),
  UNIQUE INDEX `menu_extraid_UNIQUE` (`menu_extraid` ASC),
  INDEX `fk_menu_extras_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_menu_extras_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `events` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert alle möglichen Extras die zu einer Speise bestellt werden können (miz Pommes, mit Reis, kein Salat, ...)';


-- -----------------------------------------------------
-- Table `menu_sizes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_sizes` ;

CREATE TABLE IF NOT EXISTS `menu_sizes` (
  `menu_sizeid` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(32) NOT NULL,
  `factor` DECIMAL(3,2) NOT NULL,
  PRIMARY KEY (`menu_sizeid`),
  UNIQUE INDEX `menu_sizeid_UNIQUE` (`menu_sizeid` ASC))
ENGINE = InnoDB
COMMENT = 'Enhält die verschiedene Einheitsgröße, die es für eine Speise/Getränk geben kann. (Normal, Kleine Speise, 0,25L, 0,5L, ...)';


-- -----------------------------------------------------
-- Table `menues_possible_sizes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menues_possible_sizes` ;

CREATE TABLE IF NOT EXISTS `menues_possible_sizes` (
  `menues_possible_sizeid` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_sizeid` INT(11) NOT NULL,
  `menuid` INT(11) NOT NULL,
  `price` DECIMAL(7,2) NULL,
  PRIMARY KEY (`menues_possible_sizeid`, `menu_sizeid`, `menuid`),
  INDEX `fk_menues_possible_sizes_menues1_idx` (`menuid` ASC),
  UNIQUE INDEX `menues_possible_sizeid_UNIQUE` (`menues_possible_sizeid` ASC),
  CONSTRAINT `fk_menues_possible_sizes_menu_sizes1`
    FOREIGN KEY (`menu_sizeid`)
    REFERENCES `menu_sizes` (`menu_sizeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menues_possible_sizes_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menues` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welche Größen für die verschiedene Speisen verfügbar ist mit dem angepassten Preis (Schnitzel kann als Beispiel \"Normal\" und als \"Kleine Portion\" bestellt werden. Cola in 0,25, 0,5L, ....)';


-- -----------------------------------------------------
-- Table `menues_possible_extras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menues_possible_extras` ;

CREATE TABLE IF NOT EXISTS `menues_possible_extras` (
  `menues_possible_extraid` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_extraid` INT(11) NOT NULL,
  `menuid` INT(11) NOT NULL,
  `price` DECIMAL(7,2) NOT NULL,
  PRIMARY KEY (`menues_possible_extraid`, `menu_extraid`, `menuid`),
  INDEX `fk_menues_possible_extras_menues1_idx` (`menuid` ASC),
  UNIQUE INDEX `menues_possible_extraid_UNIQUE` (`menues_possible_extraid` ASC),
  CONSTRAINT `fk_menues_possible_extras_menu_extras1`
    FOREIGN KEY (`menu_extraid`)
    REFERENCES `menu_extras` (`menu_extraid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menues_possible_extras_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menues` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welche extras für die verschiedene Speisen/Getränke verfügbar ist mit dem angepassten Preis (Schnitzel \"mit Reis\" statt Pommes, Schweinsbraten \"mit Kartoffel\" statt Knödel, Cola \"mit Zitrone\"...)';


-- -----------------------------------------------------
-- Table `orders_detail_sizes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_detail_sizes` ;

CREATE TABLE IF NOT EXISTS `orders_detail_sizes` (
  `orders_detailid` INT(11) NOT NULL,
  `menues_possible_sizeid` INT(11) NOT NULL,
  PRIMARY KEY (`orders_detailid`, `menues_possible_sizeid`),
  INDEX `fk_orders_detail_extras_menues_possible_sizes1_idx` (`menues_possible_sizeid` ASC),
  UNIQUE INDEX `UNIQUE` (`orders_detailid` ASC, `menues_possible_sizeid` ASC),
  CONSTRAINT `fk_orders_detail_extras_orders_details1`
    FOREIGN KEY (`orders_detailid`)
    REFERENCES `orders_details` (`orders_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_detail_extras_menues_possible_sizes1`
    FOREIGN KEY (`menues_possible_sizeid`)
    REFERENCES `menues_possible_sizes` (`menues_possible_sizeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Gibt an welche Größe einer Speise/eines Getränks bestellt worden ist';


-- -----------------------------------------------------
-- Table `orders_detail_extras`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_detail_extras` ;

CREATE TABLE IF NOT EXISTS `orders_detail_extras` (
  `orders_detailid` INT(11) NOT NULL,
  `menues_possible_extraid` INT(11) NOT NULL,
  PRIMARY KEY (`orders_detailid`, `menues_possible_extraid`),
  INDEX `fk_orders_detail_sizes_menues_possible_extras1_idx` (`menues_possible_extraid` ASC),
  UNIQUE INDEX `UNIQUE` (`orders_detailid` ASC, `menues_possible_extraid` ASC),
  CONSTRAINT `fk_orders_detail_sizes_orders_details1`
    FOREIGN KEY (`orders_detailid`)
    REFERENCES `orders_details` (`orders_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_detail_sizes_menues_possible_extras1`
    FOREIGN KEY (`menues_possible_extraid`)
    REFERENCES `menues_possible_extras` (`menues_possible_extraid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Gibt an welches Extra einer Speise/eines Getränkens bestellt worden ist';


-- -----------------------------------------------------
-- Table `orders_details_mixed_with`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_details_mixed_with` ;

CREATE TABLE IF NOT EXISTS `orders_details_mixed_with` (
  `orders_detailid` INT(11) NOT NULL,
  `menuid` INT(11) NOT NULL,
  PRIMARY KEY (`orders_detailid`, `menuid`),
  INDEX `fk_orders_details_mixed_with_menues1_idx` (`menuid` ASC),
  CONSTRAINT `fk_orders_details_mixed_with_orders_details1`
    FOREIGN KEY (`orders_detailid`)
    REFERENCES `orders_details` (`orders_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_mixed_with_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menues` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält Referenzen zu gespritzten Getränken (Cola gespritzt mit Mineral )';


-- -----------------------------------------------------
-- Table `events_user_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_user_role` ;

CREATE TABLE IF NOT EXISTS `events_user_role` (
  `events_user_roleid` TINYINT NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`events_user_roleid`),
  UNIQUE INDEX `user_roleid_UNIQUE` (`events_user_roleid` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Enthält die Zugriffsrechte, die Benutzer haben können';


-- -----------------------------------------------------
-- Table `orders_in_progress`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_in_progress` ;

CREATE TABLE IF NOT EXISTS `orders_in_progress` (
  `orders_in_progressid` INT(11) NOT NULL AUTO_INCREMENT,
  `orderid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  `begin` DATETIME NOT NULL,
  `done` DATETIME NULL,
  PRIMARY KEY (`orders_in_progressid`, `orderid`, `userid`),
  UNIQUE INDEX `orders_in_progressid_UNIQUE` (`orders_in_progressid` ASC),
  INDEX `fk_orders_in_progress_orders1_idx` (`orderid` ASC),
  INDEX `fk_orders_in_progress_users1_idx` (`userid` ASC),
  CONSTRAINT `fk_orders_in_progress_orders1`
    FOREIGN KEY (`orderid`)
    REFERENCES `orders` (`orderid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_in_progress_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beschreibt, welche Teile aus einer Bestellung zurzeit in Arbeit ist (Benutzer XYZ, der bei der Ausgabe ist, Bearbeitet zurzeit die Bestellungen vom Typen \"Essen\")';


-- -----------------------------------------------------
-- Table `events_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_user` ;

CREATE TABLE IF NOT EXISTS `events_user` (
  `events_userid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  `user_roles` TINYINT NOT NULL,
  `begin_money` DECIMAL NOT NULL,
  PRIMARY KEY (`events_userid`, `eventid`, `userid`),
  INDEX `fk_events_has_users_users1_idx` (`userid` ASC),
  INDEX `fk_events_has_users_events1_idx` (`eventid` ASC),
  UNIQUE INDEX `UNIQUE` (`eventid` ASC, `userid` ASC),
  UNIQUE INDEX `events_userid_UNIQUE` (`events_userid` ASC),
  CONSTRAINT `fk_events_has_users_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `events` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_has_users_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beschreibt welche Benutzer bei welchem Event, welche Rechte haben. Beispiel: Heuer ist Benutzer X der Admin, nächstes Event nur ein Kellner';


-- -----------------------------------------------------
-- Table `events_printers`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events_printers` ;

CREATE TABLE IF NOT EXISTS `events_printers` (
  `events_printerid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `ip` VARCHAR(15) NOT NULL,
  `port` SMALLINT UNSIGNED NOT NULL,
  `default` TINYINT(1) NOT NULL,
  `characters_per_row` TINYINT NOT NULL,
  PRIMARY KEY (`events_printerid`, `eventid`),
  UNIQUE INDEX `printerid_UNIQUE` (`events_printerid` ASC),
  INDEX `fk_printers_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_printers_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `events` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält die IP:Port der Drucker, die bei einem Event zur verfügung stehen';


-- -----------------------------------------------------
-- Table `distributions_places`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distributions_places` ;

CREATE TABLE IF NOT EXISTS `distributions_places` (
  `distributions_placeid` INT(11) NOT NULL,
  `eventid` INT(11) NOT NULL,
  `events_printerid` INT(11) NULL,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`distributions_placeid`, `eventid`, `events_printerid`),
  UNIQUE INDEX `events_distribution_placeid_UNIQUE` (`distributions_placeid` ASC),
  INDEX `fk_events_distribution_places_events1_idx` (`eventid` ASC),
  INDEX `fk_distributions_places_events_printers1_idx` (`events_printerid` ASC),
  CONSTRAINT `fk_events_distribution_places_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `events` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_distributions_places_events_printers1`
    FOREIGN KEY (`events_printerid`)
    REFERENCES `events_printers` (`events_printerid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltelt die Ausgabestellen, die es auf einem Event gibt. Definiert außerdem, welcher Drucker dort zur verfügung steht, um Bons zu drucken';


-- -----------------------------------------------------
-- Table `distributions_places_groupes`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distributions_places_groupes` ;

CREATE TABLE IF NOT EXISTS `distributions_places_groupes` (
  `distributions_placeid` INT(11) NOT NULL,
  `menu_groupid` INT(11) NOT NULL,
  PRIMARY KEY (`distributions_placeid`, `menu_groupid`),
  INDEX `fk_distributions_places_has_menu_groupes_menu_groupes1_idx` (`menu_groupid` ASC),
  INDEX `fk_distributions_places_has_menu_groupes_distributions_plac_idx` (`distributions_placeid` ASC),
  CONSTRAINT `fk_distributions_places_has_menu_groupes_distributions_places1`
    FOREIGN KEY (`distributions_placeid`)
    REFERENCES `distributions_places` (`distributions_placeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_distributions_places_has_menu_groupes_menu_groupes1`
    FOREIGN KEY (`menu_groupid`)
    REFERENCES `menu_groupes` (`menu_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welche Ausgaben bei einer Ausgabestelle gemacht werden (Essensausgabe: Hauptgerichte, Salat; Bar: Antigetränk, Bier, Wein, Schnaps; Süsswarne: Kuchen; ...)';


-- -----------------------------------------------------
-- Table `distributions_places_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distributions_places_users` ;

CREATE TABLE IF NOT EXISTS `distributions_places_users` (
  `distributions_placeid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  PRIMARY KEY (`distributions_placeid`, `userid`),
  INDEX `fk_distributions_places_has_users_users1_idx` (`userid` ASC),
  INDEX `fk_distributions_places_has_users_distributions_places1_idx` (`distributions_placeid` ASC),
  CONSTRAINT `fk_distributions_places_has_users_distributions_places1`
    FOREIGN KEY (`distributions_placeid`)
    REFERENCES `distributions_places` (`distributions_placeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_distributions_places_has_users_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welcher Benutzer zugriff auf die Ausgabestelle hat bzw. sich um die Ausgabestelle kümmert';


-- -----------------------------------------------------
-- Table `distribution_places_tables`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distribution_places_tables` ;

CREATE TABLE IF NOT EXISTS `distribution_places_tables` (
  `tableid` INT(11) NOT NULL,
  `distributions_placeid` INT(11) NOT NULL,
  PRIMARY KEY (`tableid`, `distributions_placeid`),
  INDEX `fk_tables_has_distributions_places_distributions_places1_idx` (`distributions_placeid` ASC),
  INDEX `fk_tables_has_distributions_places_tables1_idx` (`tableid` ASC),
  CONSTRAINT `fk_tables_has_distributions_places_tables1`
    FOREIGN KEY (`tableid`)
    REFERENCES `tables` (`tableid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_tables_has_distributions_places_distributions_places1`
    FOREIGN KEY (`distributions_placeid`)
    REFERENCES `distributions_places` (`distributions_placeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `orders_details_special_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_details_special_extra` ;

CREATE TABLE IF NOT EXISTS `orders_details_special_extra` (
  `orders_details_special_extraid` INT(11) NOT NULL AUTO_INCREMENT,
  `orderid` INT(11) NOT NULL,
  `menu_groupid` INT(11) NULL,
  `amount` TINYINT NOT NULL,
  `single_price` DECIMAL(7,2) NULL,
  `single_price_modified_by_userid` INT(11) NULL,
  `extra_detail` VARCHAR(255) NOT NULL,
  `verified` TINYINT(1) NOT NULL,
  `finished` DATETIME NULL,
  `availablility` ENUM('AVAILABLE', 'DELAYED', 'OUT OF ORDER') NULL,
  PRIMARY KEY (`orders_details_special_extraid`, `orderid`),
  UNIQUE INDEX `orders_details_special_extraid_UNIQUE` (`orders_details_special_extraid` ASC),
  INDEX `fk_orders_details_special_extra_orders1_idx` (`orderid` ASC),
  INDEX `fk_orders_details_special_extra_users1_idx` (`single_price_modified_by_userid` ASC),
  INDEX `idx_amount` (`amount` ASC),
  INDEX `fk_orders_details_special_extra_menu_groupes1_idx` (`menu_groupid` ASC),
  CONSTRAINT `fk_orders_details_special_extra_orders1`
    FOREIGN KEY (`orderid`)
    REFERENCES `orders` (`orderid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_special_extra_users1`
    FOREIGN KEY (`single_price_modified_by_userid`)
    REFERENCES `users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_special_extra_menu_groupes1`
    FOREIGN KEY (`menu_groupid`)
    REFERENCES `menu_groupes` (`menu_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `invoices`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoices` ;

CREATE TABLE IF NOT EXISTS `invoices` (
  `invoiceid` INT(11) NOT NULL AUTO_INCREMENT,
  `cashier_userid` INT(11) NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`invoiceid`, `cashier_userid`),
  UNIQUE INDEX `invoiceid_UNIQUE` (`invoiceid` ASC),
  INDEX `fk_invoices_users1_idx` (`cashier_userid` ASC),
  CONSTRAINT `fk_invoices_users1`
    FOREIGN KEY (`cashier_userid`)
    REFERENCES `users` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `invoices_orders_details`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoices_orders_details` ;

CREATE TABLE IF NOT EXISTS `invoices_orders_details` (
  `invoices_orders_detailsid` INT(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` INT(11) NOT NULL,
  `orders_detailid` INT(11) NOT NULL,
  `amount` TINYINT NOT NULL,
  PRIMARY KEY (`invoices_orders_detailsid`, `invoiceid`, `orders_detailid`),
  INDEX `fk_invoices_has_orders_details_orders_details1_idx` (`orders_detailid` ASC),
  INDEX `fk_invoices_has_orders_details_invoices1_idx` (`invoiceid` ASC),
  INDEX `idx_amount` (`amount` ASC),
  UNIQUE INDEX `invoices_orders_detailsid_UNIQUE` (`invoices_orders_detailsid` ASC),
  CONSTRAINT `fk_invoices_has_orders_details_invoices1`
    FOREIGN KEY (`invoiceid`)
    REFERENCES `invoices` (`invoiceid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoices_has_orders_details_orders_details1`
    FOREIGN KEY (`orders_detailid`)
    REFERENCES `orders_details` (`orders_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `invoices_orders_details_special_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoices_orders_details_special_extra` ;

CREATE TABLE IF NOT EXISTS `invoices_orders_details_special_extra` (
  `invoices_orders_details_special_extraid` INT(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` INT(11) NOT NULL,
  `orders_details_special_extraid` INT(11) NOT NULL,
  `amount` TINYINT NOT NULL,
  PRIMARY KEY (`invoices_orders_details_special_extraid`, `invoiceid`, `orders_details_special_extraid`),
  INDEX `fk_invoices_has_orders_details_special_extra_orders_details_idx` (`orders_details_special_extraid` ASC),
  INDEX `fk_invoices_has_orders_details_special_extra_invoices1_idx` (`invoiceid` ASC),
  INDEX `idx_amount` (`amount` ASC),
  UNIQUE INDEX `invoices_orders_details_special_extraid_UNIQUE` (`invoices_orders_details_special_extraid` ASC),
  CONSTRAINT `fk_invoices_has_orders_details_special_extra_invoices1`
    FOREIGN KEY (`invoiceid`)
    REFERENCES `invoices` (`invoiceid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoices_has_orders_details_special_extra_orders_details_s1`
    FOREIGN KEY (`orders_details_special_extraid`)
    REFERENCES `orders_details_special_extra` (`orders_details_special_extraid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `orders_in_progress_recieved`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_in_progress_recieved` ;

CREATE TABLE IF NOT EXISTS `orders_in_progress_recieved` (
  `orders_in_progress_recievedid` INT(11) NOT NULL AUTO_INCREMENT,
  `orders_detailid` INT(11) NOT NULL,
  `orders_in_progressid` INT(11) NOT NULL,
  `amount` TINYINT NOT NULL,
  `finished` DATETIME NOT NULL,
  PRIMARY KEY (`orders_in_progress_recievedid`, `orders_detailid`, `orders_in_progressid`),
  INDEX `fk_orders_details_has_orders_in_progress_orders_in_progress_idx` (`orders_in_progressid` ASC),
  INDEX `fk_orders_details_has_orders_in_progress_orders_details1_idx` (`orders_detailid` ASC),
  UNIQUE INDEX `orders_in_progress_recievedid_UNIQUE` (`orders_in_progress_recievedid` ASC),
  CONSTRAINT `fk_orders_details_has_orders_in_progress_orders_details1`
    FOREIGN KEY (`orders_detailid`)
    REFERENCES `orders_details` (`orders_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_has_orders_in_progress_orders_in_progress1`
    FOREIGN KEY (`orders_in_progressid`)
    REFERENCES `orders_in_progress` (`orders_in_progressid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `orders_extras_in_progress_recieved`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `orders_extras_in_progress_recieved` ;

CREATE TABLE IF NOT EXISTS `orders_extras_in_progress_recieved` (
  `orders_extras_in_progress_recieved` INT(11) NOT NULL AUTO_INCREMENT,
  `orders_details_special_extraid` INT(11) NOT NULL,
  `orders_in_progressid` INT(11) NOT NULL,
  `amount` TINYINT NOT NULL,
  `finished` DATETIME NOT NULL,
  PRIMARY KEY (`orders_extras_in_progress_recieved`, `orders_details_special_extraid`, `orders_in_progressid`),
  INDEX `fk_orders_details_special_extra_has_orders_in_progress_orde_idx` (`orders_in_progressid` ASC),
  INDEX `fk_orders_details_special_extra_has_orders_in_progress_orde_idx1` (`orders_details_special_extraid` ASC),
  UNIQUE INDEX `orders_extras_in_progress_recieved_UNIQUE` (`orders_extras_in_progress_recieved` ASC),
  CONSTRAINT `fk_orders_details_special_extra_has_orders_in_progress_orders1`
    FOREIGN KEY (`orders_details_special_extraid`)
    REFERENCES `orders_details_special_extra` (`orders_details_special_extraid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_special_extra_has_orders_in_progress_orders2`
    FOREIGN KEY (`orders_in_progressid`)
    REFERENCES `orders_in_progress` (`orders_in_progressid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Placeholder table for view `orders_details_open`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders_details_open` (`orderid` INT, `orders_detailid` INT, `menuid` INT, `amount` INT, `single_price` INT, `extra_detail` INT, `amount_payed` INT);

-- -----------------------------------------------------
-- Placeholder table for view `orders_details_special_extra_open`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders_details_special_extra_open` (`orderid` INT, `orders_details_special_extraid` INT, `amount` INT, `single_price` INT, `extra_detail` INT, `verified` INT, `amount_payed` INT);

-- -----------------------------------------------------
-- procedure open_orders_priority
-- -----------------------------------------------------
DROP procedure IF EXISTS `open_orders_priority`;

DELIMITER $$
CREATE PROCEDURE `open_orders_priority` ()
BEGIN
SET @rank:=0, @last_menu_groupid:=0, @last_orderid:=0;
DROP TABLE IF EXISTS tmp_open_orders_priority;
CREATE TEMPORARY TABLE tmp_open_orders_priority AS (
SELECT t.orders_detailid, t.orders_details_special_extraid, t.menu_groupid, t.orderid,
		IF(@last_menu_groupid != t.menu_groupid, @rank:=0, null) AS tmp1,
        IF(@last_orderid != t.orderid, @rank:=@rank+1, null) AS tmp2,
		IF(@last_menu_groupid != t.menu_groupid, @last_menu_groupid := t.menu_groupid, null) AS tmp3,
        IF(@last_orderid != t.orderid, @last_orderid := t.orderid, null) AS tmp4,
        @rank AS rank
FROM (SELECT orders_detailid, orders_details_special_extraid, menu_groupid, priority, orderid
FROM (SELECT od.orders_detailid, NULL AS orders_details_special_extraid, m.menu_groupid, o.priority, o.orderid
			 FROM orders o
			 INNER JOIN orders_details od ON od.orderid = o.orderid AND od.finished IS NULL
			 INNER JOIN menues m ON m.menuid = od.menuid
			 WHERE o.finished IS NULL
	  UNION
	  SELECT NULL AS orders_detailid, odse.orders_details_special_extraid, odse.menu_groupid, o.priority, o.orderid
			 FROM orders o
			 INNER JOIN orders_details_special_extra odse ON odse.orderid = o.orderid AND odse.menu_groupid IS NOT NULL AND odse.finished IS NULL
			 WHERE o.finished IS NULL) f
	  ORDER BY  menu_groupid ASC, priority ASC, orders_details_special_extraid ASC
      LIMIT 18446744073709551615) t);
END$$

DELIMITER ;

-- -----------------------------------------------------
-- View `orders_details_open`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `orders_details_open` ;
DROP TABLE IF EXISTS `orders_details_open`;
CREATE  OR REPLACE VIEW `orders_details_open` AS
SELECT od.orderid,
		od.orders_detailid,
		od.menuid,
		od.amount,
		od.single_price,
		od.extra_detail,
		(SELECT COALESCE(SUM(iod.amount), 0)
		 FROM invoices_orders_details iod
		 WHERE iod.orders_detailid = od.orders_detailid) AS amount_payed
 FROM orders_details od
 WHERE od.amount <> (SELECT COALESCE(SUM(iod2.amount), 0)
						 FROM invoices_orders_details iod2
						 WHERE iod2.orders_detailid = od.orders_detailid);

-- -----------------------------------------------------
-- View `orders_details_special_extra_open`
-- -----------------------------------------------------
DROP VIEW IF EXISTS `orders_details_special_extra_open` ;
DROP TABLE IF EXISTS `orders_details_special_extra_open`;
CREATE  OR REPLACE VIEW `orders_details_special_extra_open` AS
SELECT odse.orderid,
		odse.orders_details_special_extraid,
		odse.amount,
		odse.single_price,
		odse.extra_detail,
		odse.verified,
		(SELECT COALESCE(SUM(iodse.amount), 0)
		 FROM invoices_orders_details_special_extra iodse
		 WHERE iodse.orders_details_special_extraid = odse.orders_details_special_extraid) AS amount_payed
 FROM orders_details_special_extra odse
 WHERE odse.amount <> (SELECT COALESCE(SUM(iodse2.amount), 0)
								 FROM invoices_orders_details_special_extra iodse2
								 WHERE iodse2.orders_details_special_extraid = odse.orders_details_special_extraid);

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
