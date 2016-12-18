-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mypos
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user` ;

CREATE TABLE IF NOT EXISTS `user` (
  `userid` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(64) NOT NULL,
  `password` VARCHAR(64) NOT NULL,
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
COMMENT = 'Enthält alle Benutzer, die Zugriff auf die App haben';


-- -----------------------------------------------------
-- Table `event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event` ;

CREATE TABLE IF NOT EXISTS `event` (
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
-- Table `event_table`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_table` ;

CREATE TABLE IF NOT EXISTS `event_table` (
  `event_tableid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(32) NOT NULL,
  `data` VARCHAR(255) NULL,
  UNIQUE INDEX `tableid_UNIQUE` (`event_tableid` ASC),
  INDEX `tables_name` (`name` ASC),
  PRIMARY KEY (`event_tableid`),
  INDEX `fk_tables_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_tables_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält Tischnummer, die es gibt';


-- -----------------------------------------------------
-- Table `order`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `order` ;

CREATE TABLE IF NOT EXISTS `order` (
  `orderid` INT(11) NOT NULL AUTO_INCREMENT,
  `event_tableid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  `ordertime` DATETIME NOT NULL,
  `priority` INT NOT NULL,
  `distribution_finished` DATETIME NULL,
  `invoice_finished` DATETIME NULL,
  PRIMARY KEY (`orderid`),
  UNIQUE INDEX `oder_id_UNIQUE` (`orderid` ASC),
  INDEX `ordertime` (`ordertime` ASC),
  INDEX `fk_orders_users1_idx` (`userid` ASC),
  INDEX `fk_orders_tables_idx` (`event_tableid` ASC),
  INDEX `priority` (`priority` ASC),
  INDEX `distribution_finished` (`distribution_finished` ASC),
  INDEX `invoice_finished` (`invoice_finished` ASC),
  CONSTRAINT `fk_orders_tables`
    FOREIGN KEY (`event_tableid`)
    REFERENCES `event_table` (`event_tableid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `user` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält die Bestellungen die von einem Tisch gemacht wurden durch einen Benutzer';


-- -----------------------------------------------------
-- Table `menu_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_type` ;

CREATE TABLE IF NOT EXISTS `menu_type` (
  `menu_typeid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `tax` SMALLINT NOT NULL,
  `allowMixing` TINYINT(1) NOT NULL,
  PRIMARY KEY (`menu_typeid`),
  UNIQUE INDEX `menu_typeid_UNIQUE` (`menu_typeid` ASC),
  INDEX `fk_menu_types_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_menu_types_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält die grundlegende Nahrungstypen ( Essen, Trinken, ...) und die dafür gesetzlichen Steuern';


-- -----------------------------------------------------
-- Table `menu_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_group` ;

CREATE TABLE IF NOT EXISTS `menu_group` (
  `menu_groupid` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_typeid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`menu_groupid`),
  UNIQUE INDEX `menu_groupid_UNIQUE` (`menu_groupid` ASC),
  INDEX `fk_menu_groupes_menu_types1_idx` (`menu_typeid` ASC),
  CONSTRAINT `fk_menu_groupes_menu_types1`
    FOREIGN KEY (`menu_typeid`)
    REFERENCES `menu_type` (`menu_typeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enhält die Untergruppen der Menükarte (Hauptspeise, Beilagen, Antigetränke, Biere, ... )';


-- -----------------------------------------------------
-- Table `availability`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `availability` ;

CREATE TABLE IF NOT EXISTS `availability` (
  `availabilityid` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(24) NOT NULL,
  PRIMARY KEY (`availabilityid`),
  UNIQUE INDEX `availabilityid_UNIQUE` (`availabilityid` ASC))
ENGINE = InnoDB
COMMENT = 'Beinhaltet die möglichen verfügbarkeits statuse den Menüs/Produkte und Sonderwünsche';


-- -----------------------------------------------------
-- Table `menu`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu` ;

CREATE TABLE IF NOT EXISTS `menu` (
  `menuid` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_groupid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `price` DECIMAL(7,2) NOT NULL,
  `availabilityid` INT NOT NULL,
  `availability_amount` SMALLINT UNSIGNED NULL,
  UNIQUE INDEX `menuid_UNIQUE` (`menuid` ASC),
  INDEX `fk_menues_menu_groupes1_idx` (`menu_groupid` ASC),
  PRIMARY KEY (`menuid`),
  INDEX `fk_menues_availabilitys1_idx` (`availabilityid` ASC),
  CONSTRAINT `fk_menues_menu_groupes1`
    FOREIGN KEY (`menu_groupid`)
    REFERENCES `menu_group` (`menu_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menues_availabilitys1`
    FOREIGN KEY (`availabilityid`)
    REFERENCES `availability` (`availabilityid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enhält das Menü das Angeboten werden kann (Schnitzel, Schweinsbaraten, Cola, Sprite, Wasser, ...) mit dem Standartpreis';


-- -----------------------------------------------------
-- Table `menu_size`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_size` ;

CREATE TABLE IF NOT EXISTS `menu_size` (
  `menu_sizeid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(32) NOT NULL,
  `factor` DECIMAL(3,2) NOT NULL,
  PRIMARY KEY (`menu_sizeid`),
  UNIQUE INDEX `menu_sizeid_UNIQUE` (`menu_sizeid` ASC),
  INDEX `fk_menu_sizes_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_menu_sizes_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enhält die verschiedene Einheitsgröße, die es für eine Speise/Getränk geben kann. (Normal, Kleine Speise, 0,25L, 0,5L, ...)';


-- -----------------------------------------------------
-- Table `order_detail`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `order_detail` ;

CREATE TABLE IF NOT EXISTS `order_detail` (
  `order_detailid` INT(11) NOT NULL AUTO_INCREMENT,
  `orderid` INT(11) NOT NULL,
  `menuid` INT(11) NULL,
  `menu_sizeid` INT(11) NULL,
  `menu_groupid` INT(11) NULL,
  `amount` TINYINT NOT NULL,
  `single_price` DECIMAL(7,2) NOT NULL,
  `single_price_modified_by_userid` INT(11) NULL,
  `extra_detail` VARCHAR(255) NULL,
  `availabilityid` INT NULL,
  `availability_amount` SMALLINT NULL,
  `verified` TINYINT(1) NOT NULL,
  `distribution_finished` DATETIME NULL,
  `invoice_finished` DATETIME NULL,
  PRIMARY KEY (`order_detailid`),
  UNIQUE INDEX `orders_detailid_UNIQUE` (`order_detailid` ASC),
  INDEX `fk_orders_details_menues1_idx` (`menuid` ASC),
  INDEX `fk_orders_details_orders1_idx` (`orderid` ASC),
  INDEX `fk_orders_details_users1_idx` (`single_price_modified_by_userid` ASC),
  INDEX `idx_amount` (`amount` ASC),
  INDEX `fk_orders_details_menu_sizes1_idx` (`menu_sizeid` ASC),
  INDEX `fk_orders_details_menu_groupes1_idx` (`menu_groupid` ASC),
  INDEX `fk_orders_details_availabilitys1_idx` (`availabilityid` ASC),
  INDEX `distribution_finished` (`distribution_finished` ASC),
  INDEX `invoice_finished` (`invoice_finished` ASC),
  CONSTRAINT `fk_orders_details_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menu` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_orders1`
    FOREIGN KEY (`orderid`)
    REFERENCES `order` (`orderid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_users1`
    FOREIGN KEY (`single_price_modified_by_userid`)
    REFERENCES `user` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_menu_sizes1`
    FOREIGN KEY (`menu_sizeid`)
    REFERENCES `menu_size` (`menu_sizeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_menu_groupes1`
    FOREIGN KEY (`menu_groupid`)
    REFERENCES `menu_group` (`menu_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_availabilitys1`
    FOREIGN KEY (`availabilityid`)
    REFERENCES `availability` (`availabilityid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält die genauen Bestell-Positionen einner Bestellung, die getätigte Zahlung dafür und wie oft diese Position bestellt wurde. menuid is leer(0), wenn es sich um einen \"extrawunsch\" handelt.';


-- -----------------------------------------------------
-- Table `menu_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_extra` ;

CREATE TABLE IF NOT EXISTS `menu_extra` (
  `menu_extraid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `availabilityid` INT NOT NULL,
  `availability_amount` SMALLINT UNSIGNED NULL,
  PRIMARY KEY (`menu_extraid`),
  UNIQUE INDEX `menu_extraid_UNIQUE` (`menu_extraid` ASC),
  INDEX `fk_menu_extras_events1_idx` (`eventid` ASC),
  INDEX `fk_menu_extras_availabilitys1_idx` (`availabilityid` ASC),
  CONSTRAINT `fk_menu_extras_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menu_extras_availabilitys1`
    FOREIGN KEY (`availabilityid`)
    REFERENCES `availability` (`availabilityid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert alle möglichen Extras die zu einer Speise bestellt werden können (miz Pommes, mit Reis, kein Salat, ...)';


-- -----------------------------------------------------
-- Table `menu_possible_size`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_possible_size` ;

CREATE TABLE IF NOT EXISTS `menu_possible_size` (
  `menu_possible_sizeid` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_sizeid` INT(11) NOT NULL,
  `menuid` INT(11) NOT NULL,
  `price` DECIMAL(7,2) NULL,
  PRIMARY KEY (`menu_possible_sizeid`),
  INDEX `fk_menues_possible_sizes_menues1_idx` (`menuid` ASC),
  UNIQUE INDEX `menues_possible_sizeid_UNIQUE` (`menu_possible_sizeid` ASC),
  CONSTRAINT `fk_menues_possible_sizes_menu_sizes1`
    FOREIGN KEY (`menu_sizeid`)
    REFERENCES `menu_size` (`menu_sizeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menues_possible_sizes_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menu` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welche Größen für die verschiedene Speisen verfügbar ist mit dem angepassten Preis (Schnitzel kann als Beispiel \"Normal\" und als \"Kleine Portion\" bestellt werden. Cola in 0,25, 0,5L, ....)';


-- -----------------------------------------------------
-- Table `menu_possible_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `menu_possible_extra` ;

CREATE TABLE IF NOT EXISTS `menu_possible_extra` (
  `menu_possible_extraid` INT(11) NOT NULL AUTO_INCREMENT,
  `menu_extraid` INT(11) NOT NULL,
  `menuid` INT(11) NOT NULL,
  `price` DECIMAL(7,2) NOT NULL,
  PRIMARY KEY (`menu_possible_extraid`),
  INDEX `fk_menues_possible_extras_menues1_idx` (`menuid` ASC),
  UNIQUE INDEX `menues_possible_extraid_UNIQUE` (`menu_possible_extraid` ASC),
  CONSTRAINT `fk_menues_possible_extras_menu_extras1`
    FOREIGN KEY (`menu_extraid`)
    REFERENCES `menu_extra` (`menu_extraid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_menues_possible_extras_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menu` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welche extras für die verschiedene Speisen/Getränke verfügbar ist mit dem angepassten Preis (Schnitzel \"mit Reis\" statt Pommes, Schweinsbraten \"mit Kartoffel\" statt Knödel, Cola \"mit Zitrone\"...)';


-- -----------------------------------------------------
-- Table `order_detail_extra`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `order_detail_extra` ;

CREATE TABLE IF NOT EXISTS `order_detail_extra` (
  `order_detailid` INT(11) NOT NULL,
  `menu_possible_extraid` INT(11) NOT NULL,
  PRIMARY KEY (`order_detailid`, `menu_possible_extraid`),
  INDEX `fk_orders_detail_sizes_menues_possible_extras1_idx` (`menu_possible_extraid` ASC),
  UNIQUE INDEX `UNIQUE` (`order_detailid` ASC, `menu_possible_extraid` ASC),
  CONSTRAINT `fk_orders_detail_sizes_orders_details1`
    FOREIGN KEY (`order_detailid`)
    REFERENCES `order_detail` (`order_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_detail_sizes_menues_possible_extras1`
    FOREIGN KEY (`menu_possible_extraid`)
    REFERENCES `menu_possible_extra` (`menu_possible_extraid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Gibt an welches Extra einer Speise/eines Getränkens bestellt worden ist';


-- -----------------------------------------------------
-- Table `order_detail_mixed_with`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `order_detail_mixed_with` ;

CREATE TABLE IF NOT EXISTS `order_detail_mixed_with` (
  `order_detailid` INT(11) NOT NULL,
  `menuid` INT(11) NOT NULL,
  PRIMARY KEY (`order_detailid`, `menuid`),
  INDEX `fk_orders_details_mixed_with_menues1_idx` (`menuid` ASC),
  CONSTRAINT `fk_orders_details_mixed_with_orders_details1`
    FOREIGN KEY (`order_detailid`)
    REFERENCES `order_detail` (`order_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_mixed_with_menues1`
    FOREIGN KEY (`menuid`)
    REFERENCES `menu` (`menuid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält Referenzen zu gespritzten Getränken (Cola gespritzt mit Mineral )';


-- -----------------------------------------------------
-- Table `user_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_role` ;

CREATE TABLE IF NOT EXISTS `user_role` (
  `user_roleid` INT(11) UNSIGNED NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`user_roleid`),
  UNIQUE INDEX `user_roleid_UNIQUE` (`user_roleid` ASC),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC))
ENGINE = InnoDB
COMMENT = 'Enthält die Zugriffsrechte, die Benutzer haben können';


-- -----------------------------------------------------
-- Table `order_in_progress`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `order_in_progress` ;

CREATE TABLE IF NOT EXISTS `order_in_progress` (
  `order_in_progressid` INT(11) NOT NULL,
  `orderid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  `menu_groupid` INT(11) NOT NULL,
  `begin` DATETIME NOT NULL,
  `done` DATETIME NULL,
  PRIMARY KEY (`order_in_progressid`),
  UNIQUE INDEX `orders_in_progressid_UNIQUE` (`order_in_progressid` ASC),
  INDEX `fk_orders_in_progress_orders1_idx` (`orderid` ASC),
  INDEX `fk_orders_in_progress_users1_idx` (`userid` ASC),
  INDEX `fk_orders_in_progress_menu_groupes1_idx` (`menu_groupid` ASC),
  CONSTRAINT `fk_orders_in_progress_orders1`
    FOREIGN KEY (`orderid`)
    REFERENCES `order` (`orderid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_in_progress_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `user` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_in_progress_menu_groupes1`
    FOREIGN KEY (`menu_groupid`)
    REFERENCES `menu_group` (`menu_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beschreibt, welche Teile aus einer Bestellung zurzeit in Arbeit ist (Benutzer XYZ, der bei der Ausgabe \"Bierbar\" ist, Bearbeitet zurzeit die Bestellungen von  der Gruppe \"Biere\") Benutzer kann auch mehrere gleichzeitig bearbeiten (Beispiel: Benutzer A bearbeitet zur zeit die Bestellung XYZ und kümmert sich um die Menü Gruppen \"Antigetränk\", \"Biere\". Benutzer B kümmert sich um die Menügruppe \"Wein\". Kann auch eine andere Ausgabestelle sein, falls nötig.';


-- -----------------------------------------------------
-- Table `event_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_user` ;

CREATE TABLE IF NOT EXISTS `event_user` (
  `event_userid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  `user_roles` INT(11) UNSIGNED NOT NULL,
  `begin_money` DECIMAL NOT NULL,
  PRIMARY KEY (`event_userid`),
  INDEX `fk_events_has_users_users1_idx` (`userid` ASC),
  INDEX `fk_events_has_users_events1_idx` (`eventid` ASC),
  UNIQUE INDEX `UNIQUE` (`eventid` ASC, `userid` ASC),
  UNIQUE INDEX `events_userid_UNIQUE` (`event_userid` ASC),
  CONSTRAINT `fk_events_has_users_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_events_has_users_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `user` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beschreibt welche Benutzer bei welchem Event, welche Rechte haben. Beispiel: Heuer ist Benutzer X der Admin, nächstes Event nur ein Kellner';


-- -----------------------------------------------------
-- Table `distribution_place`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distribution_place` ;

CREATE TABLE IF NOT EXISTS `distribution_place` (
  `distribution_placeid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`distribution_placeid`),
  UNIQUE INDEX `events_distribution_placeid_UNIQUE` (`distribution_placeid` ASC),
  INDEX `fk_events_distribution_places_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_events_distribution_places_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltelt die Ausgabestellen, die es auf einem Event gibt. n';


-- -----------------------------------------------------
-- Table `event_printer`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_printer` ;

CREATE TABLE IF NOT EXISTS `event_printer` (
  `event_printerid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `type` SMALLINT NOT NULL,
  `attr1` VARCHAR(128) NULL,
  `attr2` VARCHAR(128) NULL,
  `default` TINYINT(1) NOT NULL,
  `characters_per_row` TINYINT NOT NULL,
  PRIMARY KEY (`event_printerid`),
  UNIQUE INDEX `printerid_UNIQUE` (`event_printerid` ASC),
  INDEX `fk_printers_events1_idx` (`eventid` ASC),
  CONSTRAINT `fk_printers_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Enthält die Konfiguration der Drucker, die bei einem Event zur verfügung stehen\nType:\n1: Network\n2: File\n3: Windows\n4: Cups\n5: Dummy';


-- -----------------------------------------------------
-- Table `distribution_place_group`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distribution_place_group` ;

CREATE TABLE IF NOT EXISTS `distribution_place_group` (
  `distribution_place_groupid` INT(11) NOT NULL AUTO_INCREMENT,
  `distribution_placeid` INT(11) NOT NULL,
  `menu_groupid` INT(11) NOT NULL,
  PRIMARY KEY (`distribution_place_groupid`),
  INDEX `fk_distributions_places_has_menu_groupes_menu_groupes1_idx` (`menu_groupid` ASC),
  INDEX `fk_distributions_places_has_menu_groupes_distributions_plac_idx` (`distribution_placeid` ASC),
  UNIQUE INDEX `distribution_place_groupid_UNIQUE` (`distribution_place_groupid` ASC),
  CONSTRAINT `fk_distributions_places_has_menu_groupes_distributions_places1`
    FOREIGN KEY (`distribution_placeid`)
    REFERENCES `distribution_place` (`distribution_placeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_distributions_places_has_menu_groupes_menu_groupes1`
    FOREIGN KEY (`menu_groupid`)
    REFERENCES `menu_group` (`menu_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welche Ausgaben bei einer Ausgabestelle gemacht werden (Essensausgabe: Hauptgerichte, Salat; Bar: Antigetränk, Bier, Wein, Schnaps; Süsswarne: Kuchen; ...)';


-- -----------------------------------------------------
-- Table `distribution_place_user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distribution_place_user` ;

CREATE TABLE IF NOT EXISTS `distribution_place_user` (
  `distribution_placeid` INT(11) NOT NULL,
  `userid` INT(11) NOT NULL,
  `event_printerid` INT(11) NOT NULL,
  PRIMARY KEY (`distribution_placeid`, `userid`, `event_printerid`),
  INDEX `fk_distributions_places_has_users_users1_idx` (`userid` ASC),
  INDEX `fk_distributions_places_has_users_distributions_places1_idx` (`distribution_placeid` ASC),
  INDEX `fk_distributions_places_users_events_printers1_idx` (`event_printerid` ASC),
  CONSTRAINT `fk_distributions_places_has_users_distributions_places1`
    FOREIGN KEY (`distribution_placeid`)
    REFERENCES `distribution_place` (`distribution_placeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_distributions_places_has_users_users1`
    FOREIGN KEY (`userid`)
    REFERENCES `user` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_distributions_places_users_events_printers1`
    FOREIGN KEY (`event_printerid`)
    REFERENCES `event_printer` (`event_printerid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert, welcher Benutzer zugriff auf die Ausgabestelle hat bzw. sich um die Ausgabestelle kümmert. Definiert außerdem, welcher Drucker im dort zur verfügung steht, um Bons zu drucke';


-- -----------------------------------------------------
-- Table `distribution_place_table`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distribution_place_table` ;

CREATE TABLE IF NOT EXISTS `distribution_place_table` (
  `event_tableid` INT(11) NOT NULL,
  `distribution_place_groupid` INT(11) NOT NULL,
  PRIMARY KEY (`event_tableid`, `distribution_place_groupid`),
  INDEX `fk_tables_has_distributions_places_tables1_idx` (`event_tableid` ASC),
  INDEX `fk_distribution_place_table_distribution_place_group1_idx` (`distribution_place_groupid` ASC),
  CONSTRAINT `fk_tables_has_distributions_places_tables1`
    FOREIGN KEY (`event_tableid`)
    REFERENCES `event_table` (`event_tableid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_distribution_place_table_distribution_place_group1`
    FOREIGN KEY (`distribution_place_groupid`)
    REFERENCES `distribution_place_group` (`distribution_place_groupid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Definiert welche tische standartmäßg von wo ihre Menüs erhalten. Jeder\nMenü Gruppe kann einem eigenem Ausgabeort zugeteilt werden';


-- -----------------------------------------------------
-- Table `event_contact`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_contact` ;

CREATE TABLE IF NOT EXISTS `event_contact` (
  `event_contactid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `title` VARCHAR(32) NOT NULL,
  `name` VARCHAR(128) NOT NULL,
  `contact_person` VARCHAR(128) NULL,
  `address` VARCHAR(128) NOT NULL,
  `address2` VARCHAR(128) NULL,
  `city` VARCHAR(64) NOT NULL,
  `zip` VARCHAR(10) NOT NULL,
  `tax_identification_nr` VARCHAR(32) NULL,
  `telephon` VARCHAR(32) NULL,
  `fax` VARCHAR(32) NULL,
  `email` VARCHAR(254) NULL,
  `active` TINYINT(1) NOT NULL,
  `default` TINYINT(1) NOT NULL,
  PRIMARY KEY (`event_contactid`),
  UNIQUE INDEX `customerid_UNIQUE` (`event_contactid` ASC),
  INDEX `fk_customer_event1_idx` (`eventid` ASC),
  INDEX `name` (`name` ASC),
  INDEX `active` (`active` ASC),
  INDEX `default` (`default` ASC),
  CONSTRAINT `fk_customer_event1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltet Kundendaten für Rechnungen. Wenn ein Kunde eine Vorsteuerabzugsberechtige Rechnugn braucht müssen die Kundendaten auf der Rechnung stehen genauso wie die Daten der Firma die die Rechnung ausstellt';


-- -----------------------------------------------------
-- Table `invoice_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoice_type` ;

CREATE TABLE IF NOT EXISTS `invoice_type` (
  `invoice_typeid` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(64) NOT NULL,
  PRIMARY KEY (`invoice_typeid`),
  UNIQUE INDEX `invoice_typeid_UNIQUE` (`invoice_typeid` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `event_bankinformation`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `event_bankinformation` ;

CREATE TABLE IF NOT EXISTS `event_bankinformation` (
  `event_bankinformationid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `iban` VARCHAR(32) NOT NULL,
  `bic` VARCHAR(16) NOT NULL,
  `active` TINYINT(1) NOT NULL,
  PRIMARY KEY (`event_bankinformationid`),
  UNIQUE INDEX `event_bankinformation_UNIQUE` (`event_bankinformationid` ASC),
  INDEX `fk_event_bankinformation_event1_idx` (`eventid` ASC),
  CONSTRAINT `fk_event_bankinformation_event1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `invoice`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoice` ;

CREATE TABLE IF NOT EXISTS `invoice` (
  `invoiceid` INT(11) NOT NULL AUTO_INCREMENT,
  `invoice_typeid` INT(11) NOT NULL,
  `event_contactid` INT(11) NOT NULL,
  `cashier_userid` INT(11) NOT NULL,
  `event_bankinformationid` INT(11) NOT NULL,
  `customer_event_contactid` INT(11) NULL,
  `canceled_invoiceid` INT(11) NULL,
  `date` DATETIME NOT NULL,
  `amount` DECIMAL(7,2) NOT NULL,
  `maturity_date` DATETIME NOT NULL,
  `payment_finished` DATETIME NULL,
  `amount_recieved` DECIMAL(7,2) NULL,
  PRIMARY KEY (`invoiceid`),
  UNIQUE INDEX `invoiceid_UNIQUE` (`invoiceid` ASC),
  INDEX `fk_invoices_users1_idx` (`cashier_userid` ASC),
  INDEX `fk_invoice_customer1_idx` (`customer_event_contactid` ASC),
  INDEX `payment_finished` (`payment_finished` ASC),
  INDEX `fk_invoice_event_contact1_idx` (`event_contactid` ASC),
  INDEX `fk_invoice_invoice_type1_idx` (`invoice_typeid` ASC),
  INDEX `fk_invoice_invoice1_idx` (`canceled_invoiceid` ASC),
  INDEX `fk_invoice_event_bankinformation1_idx` (`event_bankinformationid` ASC),
  CONSTRAINT `fk_invoices_users1`
    FOREIGN KEY (`cashier_userid`)
    REFERENCES `user` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_customer1`
    FOREIGN KEY (`customer_event_contactid`)
    REFERENCES `event_contact` (`event_contactid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_event_contact1`
    FOREIGN KEY (`event_contactid`)
    REFERENCES `event_contact` (`event_contactid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_invoice_type1`
    FOREIGN KEY (`invoice_typeid`)
    REFERENCES `invoice_type` (`invoice_typeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_invoice1`
    FOREIGN KEY (`canceled_invoiceid`)
    REFERENCES `invoice` (`invoiceid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoice_event_bankinformation1`
    FOREIGN KEY (`event_bankinformationid`)
    REFERENCES `event_bankinformation` (`event_bankinformationid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltet die Rechnungsnummer mit dem Aussstellungsdatum, die es gibt. Auserdem ob Rechnung storniert wurde';


-- -----------------------------------------------------
-- Table `invoice_item`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoice_item` ;

CREATE TABLE IF NOT EXISTS `invoice_item` (
  `invoice_itemid` INT(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` INT(11) NOT NULL,
  `order_detailid` INT(11) NULL,
  `amount` TINYINT NOT NULL,
  `price` DECIMAL(7,2) NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `tax` SMALLINT NOT NULL,
  PRIMARY KEY (`invoice_itemid`),
  INDEX `fk_invoices_has_orders_details_orders_details1_idx` (`order_detailid` ASC),
  INDEX `fk_invoices_has_orders_details_invoices1_idx` (`invoiceid` ASC),
  INDEX `idx_amount` (`amount` ASC),
  UNIQUE INDEX `invoices_orders_detailsid_UNIQUE` (`invoice_itemid` ASC),
  CONSTRAINT `fk_invoices_has_orders_details_invoices1`
    FOREIGN KEY (`invoiceid`)
    REFERENCES `invoice` (`invoiceid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_invoices_has_orders_details_orders_details1`
    FOREIGN KEY (`order_detailid`)
    REFERENCES `order_detail` (`order_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltet die Zeilen einer Rechnung. Gibt an wieviel, von einer Bestellung bezahlt worden ist. Preis wird der Bestellung entnommen';


-- -----------------------------------------------------
-- Table `distribution_giving_out`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `distribution_giving_out` ;

CREATE TABLE IF NOT EXISTS `distribution_giving_out` (
  `distribution_giving_outid` INT(11) NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`distribution_giving_outid`),
  UNIQUE INDEX `distribution_givin_outid_UNIQUE` (`distribution_giving_outid` ASC))
ENGINE = InnoDB
COMMENT = 'Jede einzelne Ausgabe wird hier angelegt mit einem Zeitstempel';


-- -----------------------------------------------------
-- Table `order_in_progress_recieved`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `order_in_progress_recieved` ;

CREATE TABLE IF NOT EXISTS `order_in_progress_recieved` (
  `order_in_progress_recievedid` INT(11) NOT NULL AUTO_INCREMENT,
  `order_detailid` INT(11) NOT NULL,
  `order_in_progressid` INT(11) NOT NULL,
  `distribution_giving_outid` INT(11) NOT NULL,
  `amount` TINYINT NOT NULL,
  PRIMARY KEY (`order_in_progress_recievedid`),
  INDEX `fk_orders_details_has_orders_in_progress_orders_in_progress_idx` (`order_in_progressid` ASC),
  INDEX `fk_orders_details_has_orders_in_progress_orders_details1_idx` (`order_detailid` ASC),
  UNIQUE INDEX `orders_in_progress_recievedid_UNIQUE` (`order_in_progress_recievedid` ASC),
  INDEX `fk_orders_in_progress_recieved_distribution_givin_out1_idx` (`distribution_giving_outid` ASC),
  CONSTRAINT `fk_orders_details_has_orders_in_progress_orders_details1`
    FOREIGN KEY (`order_detailid`)
    REFERENCES `order_detail` (`order_detailid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_details_has_orders_in_progress_orders_in_progress1`
    FOREIGN KEY (`order_in_progressid`)
    REFERENCES `order_in_progress` (`order_in_progressid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_orders_in_progress_recieved_distribution_givin_out1`
    FOREIGN KEY (`distribution_giving_outid`)
    REFERENCES `distribution_giving_out` (`distribution_giving_outid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Gibt an, wann und wieviel ein Kunde bereits von seiner Bestellung erhalten hat. Erhalt kann aufgeteitl sein falls zbs. nur mehr 2 Schnitzel vorhanden sind und 3 Bestellt wurden. 1 wird später nachgeliefert und ist ein eigener eintrag';


-- -----------------------------------------------------
-- Table `user_message`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `user_message` ;

CREATE TABLE IF NOT EXISTS `user_message` (
  `user_messageid` INT(11) NOT NULL,
  `from_event_userid` INT(11) NULL,
  `to_event_userid` INT(11) NOT NULL,
  `message` TEXT NOT NULL,
  `date` DATETIME NOT NULL,
  `readed` TINYINT(1) NOT NULL,
  PRIMARY KEY (`user_messageid`),
  UNIQUE INDEX `users_chatid_UNIQUE` (`user_messageid` ASC),
  INDEX `fk_users_chat_events_user1_idx` (`from_event_userid` ASC),
  INDEX `fk_users_chat_events_user2_idx` (`to_event_userid` ASC),
  INDEX `index5` (`date` ASC),
  CONSTRAINT `fk_users_chat_events_user1`
    FOREIGN KEY (`from_event_userid`)
    REFERENCES `event_user` (`event_userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_chat_events_user2`
    FOREIGN KEY (`to_event_userid`)
    REFERENCES `event_user` (`event_userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltet die Kommunikationsnachrichten zwischen den Benutzern für den internen Chat. Auserdem beinhaltet es Systemnachrichten an die Benutzer (keine\'from_events_userid\')';


-- -----------------------------------------------------
-- Table `payment_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `payment_type` ;

CREATE TABLE IF NOT EXISTS `payment_type` (
  `payment_typeid` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(24) NOT NULL,
  PRIMARY KEY (`payment_typeid`),
  UNIQUE INDEX `idpayment_typeid_UNIQUE` (`payment_typeid` ASC))
ENGINE = InnoDB
COMMENT = 'Beinhaltet bezahlarten';


-- -----------------------------------------------------
-- Table `coupon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `coupon` ;

CREATE TABLE IF NOT EXISTS `coupon` (
  `couponid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `created_by_userid` INT(11) NOT NULL,
  `code` VARCHAR(24) NOT NULL,
  `created` DATETIME NOT NULL,
  `value` DECIMAL(7,2) NOT NULL,
  PRIMARY KEY (`couponid`),
  INDEX `fk_Coupons_events1_idx` (`eventid` ASC),
  INDEX `fk_Coupons_users1_idx` (`created_by_userid` ASC),
  INDEX `code` (`code` ASC),
  UNIQUE INDEX `couponid_UNIQUE` (`couponid` ASC),
  CONSTRAINT `fk_Coupons_events1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Coupons_users1`
    FOREIGN KEY (`created_by_userid`)
    REFERENCES `user` (`userid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltet ausgegebene Gutscheine';


-- -----------------------------------------------------
-- Table `payment_recieved`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `payment_recieved` ;

CREATE TABLE IF NOT EXISTS `payment_recieved` (
  `payment_recievedid` INT(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` INT(11) NOT NULL,
  `payment_typeid` INT(11) NOT NULL,
  `date` DATETIME NOT NULL,
  `amount` DECIMAL(7,2) NOT NULL,
  PRIMARY KEY (`payment_recievedid`),
  UNIQUE INDEX `payment_recievedid_UNIQUE` (`payment_recievedid` ASC),
  INDEX `fk_payment_recieved_payment_type1_idx` (`payment_typeid` ASC),
  INDEX `fk_payment_recieved_invoice1_idx` (`invoiceid` ASC),
  CONSTRAINT `fk_payment_recieved_payment_type1`
    FOREIGN KEY (`payment_typeid`)
    REFERENCES `payment_type` (`payment_typeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payment_recieved_invoice1`
    FOREIGN KEY (`invoiceid`)
    REFERENCES `invoice` (`invoiceid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `payment_coupon`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `payment_coupon` ;

CREATE TABLE IF NOT EXISTS `payment_coupon` (
  `couponid` INT(11) NOT NULL,
  `payment_recievedid` INT(11) NOT NULL,
  `value_used` DECIMAL(7,2) NOT NULL,
  PRIMARY KEY (`couponid`, `payment_recievedid`),
  INDEX `fk_Coupons_has_payments_Coupons1_idx` (`couponid` ASC),
  INDEX `fk_payment_coupon_payment_recieved1_idx` (`payment_recievedid` ASC),
  CONSTRAINT `fk_Coupons_has_payments_Coupons1`
    FOREIGN KEY (`couponid`)
    REFERENCES `coupon` (`couponid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payment_coupon_payment_recieved1`
    FOREIGN KEY (`payment_recievedid`)
    REFERENCES `payment_recieved` (`payment_recievedid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
COMMENT = 'Beinhaltet Gutscheine die für eine Bezahlung verwendet wurden';


-- -----------------------------------------------------
-- Table `invoice_warning_type`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoice_warning_type` ;

CREATE TABLE IF NOT EXISTS `invoice_warning_type` (
  `invoice_warning_typeid` INT(11) NOT NULL AUTO_INCREMENT,
  `eventid` INT(11) NOT NULL,
  `name` VARCHAR(64) NOT NULL,
  `extra_price` DECIMAL(7,2) NOT NULL,
  PRIMARY KEY (`invoice_warning_typeid`),
  INDEX `fk_payment_warning_type_event1_idx` (`eventid` ASC),
  UNIQUE INDEX `payment_warning_typeid_UNIQUE` (`invoice_warning_typeid` ASC),
  CONSTRAINT `fk_payment_warning_type_event1`
    FOREIGN KEY (`eventid`)
    REFERENCES `event` (`eventid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `invoice_warning`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `invoice_warning` ;

CREATE TABLE IF NOT EXISTS `invoice_warning` (
  `invoice_warningid` INT(11) NOT NULL AUTO_INCREMENT,
  `invoiceid` INT(11) NOT NULL,
  `invoice_warning_typeid` INT(11) NOT NULL,
  `warning_date` DATETIME NOT NULL,
  `maturity_date` DATETIME NOT NULL,
  `warning_value` DECIMAL(7,2) NOT NULL,
  PRIMARY KEY (`invoice_warningid`),
  INDEX `fk_payment_warning_payment_warning_type1_idx` (`invoice_warning_typeid` ASC),
  UNIQUE INDEX `payment_warningid_UNIQUE` (`invoice_warningid` ASC),
  INDEX `fk_payment_warning_invoice1_idx` (`invoiceid` ASC),
  CONSTRAINT `fk_payment_warning_payment_warning_type1`
    FOREIGN KEY (`invoice_warning_typeid`)
    REFERENCES `invoice_warning_type` (`invoice_warning_typeid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_payment_warning_invoice1`
    FOREIGN KEY (`invoiceid`)
    REFERENCES `invoice` (`invoiceid`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- procedure open_order_priority
-- -----------------------------------------------------
DROP procedure IF EXISTS `open_order_priority`;

DELIMITER $$
CREATE PROCEDURE `open_order_priority` ()
BEGIN
SET @rank:=0, @last_menu_groupid:=0, @last_orderid:=0;
DROP TABLE IF EXISTS tmp_open_order_priority;
CREATE TEMPORARY TABLE tmp_open_order_priority AS (
SELECT t.order_detailid, t.menu_groupid, t.orderid,
		IF(@last_menu_groupid != t.menu_groupid, @rank:=0, null) AS tmp1,
        IF(@last_orderid != t.orderid, @rank:=@rank+1, null) AS tmp2,
		IF(@last_menu_groupid != t.menu_groupid, @last_menu_groupid := t.menu_groupid, null) AS tmp3,
        IF(@last_orderid != t.orderid, @last_orderid := t.orderid, null) AS tmp4,
        @rank AS rank
FROM (SELECT order_detailid, menu_groupid, priority, orderid
FROM (SELECT od.order_detailid, m.menu_groupid, o.priority, o.orderid
			 FROM `order` o
			 INNER JOIN order_details od ON od.orderid = o.orderid AND od.finished IS NULL
			 INNER JOIN menu m ON m.menuid = od.menuid
			 WHERE o.finished IS NULL) f
	  ORDER BY  menu_groupid ASC, priority ASC
      LIMIT 18446744073709551615) t);
END$$

DELIMITER ;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;