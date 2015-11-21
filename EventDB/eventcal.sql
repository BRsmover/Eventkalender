-- MySQL Script generated by MySQL Workbench
-- 11/10/15 14:05:42
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema Eventkalender
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema Eventkalender
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `Eventkalender` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `Eventkalender` ;

-- -----------------------------------------------------
-- Table `Eventkalender`.`genre`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Eventkalender`.`genre` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Eventkalender`.`veranstaltung`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Eventkalender`.`veranstaltung` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `besetzung` VARCHAR(255) NULL,
  `beschreibung` TEXT NOT NULL,
  `termin` DATETIME NOT NULL,
  `dauer` SMALLINT UNSIGNED NOT NULL,
  `bild` VARCHAR(100) NOT NULL,
  `bildbeschreibung` VARCHAR(255) NOT NULL,
  `link` VARCHAR(100) NULL,
  `linkbeschreibung` VARCHAR(255) NULL,
  `fk_genre_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Veranstaltung_Genre1_idx` (`fk_genre_id` ASC),
  CONSTRAINT `fk_Veranstaltung_Genre1`
    FOREIGN KEY (`fk_genre_id`)
    REFERENCES `Eventkalender`.`genre` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Eventkalender`.`preisgruppe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Eventkalender`.`preisgruppe` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(20) NOT NULL,
  `preis` VARCHAR(20) NOT NULL,
  PRIMARY KEY (`ID`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Eventkalender`.`veranstaltung_hat_preisgruppe`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Eventkalender`.`veranstaltung_hat_preisgruppe` (
  `fk_preisgruppe_id` INT UNSIGNED NOT NULL,
  `fk_veranstaltung_id` INT UNSIGNED NOT NULL,
  INDEX `fk_Veranstaltung_hat_Presigruppen_Preisgruppe_idx` (`fk_preisgruppe_id` ASC),
  INDEX `fk_Veranstaltung_hat_Presigruppen_Veranstaltung1_idx` (`fk_veranstaltung_id` ASC),
  CONSTRAINT `fk_Veranstaltung_hat_Presigruppen_Preisgruppe`
    FOREIGN KEY (`fk_preisgruppe_id`)
    REFERENCES `Eventkalender`.`preisgruppe` (`ID`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Veranstaltung_hat_Presigruppen_Veranstaltung1`
    FOREIGN KEY (`fk_veranstaltung_id`)
    REFERENCES `Eventkalender`.`veranstaltung` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `Eventkalender`.`benutzer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `Eventkalender`.`benutzer` (
  `ID` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `benutzername` VARCHAR(45) NOT NULL,
  `passwort` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `benutzername_UNIQUE` (`benutzername` ASC))
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;