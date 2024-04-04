-- Sat Jul 22 18:25:09 2023
-- Model: DBooks    Version: 1.0

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema booksDB
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema booksDB
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `booksDB` DEFAULT CHARACTER SET utf8 ;
USE `booksDB` ;

-- -----------------------------------------------------
-- Table `booksDB`.`Locations`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `booksDB`.`Locations` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `booksDB`.`Books`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `booksDB`.`Books` (
  `isbn` BIGINT UNSIGNED NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `description` VARCHAR(1000) NOT NULL,
  `publisherName` VARCHAR(200) NOT NULL,
  `idLocation` INT NOT NULL,
  `pageCount` INT NOT NULL,
  `languageCode` CHAR(2) NOT NULL,
  `publishedDate` DATE NOT NULL,
  `idImage` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`isbn`),
  INDEX `fk_Books_Locations_idx` (`idLocation` ASC) VISIBLE,
  CONSTRAINT `fk_Books_Locations`
    FOREIGN KEY (`idLocation`)
    REFERENCES `booksDB`.`Locations` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  UNIQUE INDEX `unique_idImage` (`idImage`),
  FULLTEXT `fulltext_Title` (`title`)
  )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `booksDB`.`Authors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `booksDB`.`Authors` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `olid` VARCHAR(50) NOT NULL,
  `name` VARCHAR(400) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `booksDB`.`Books_Authors`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `booksDB`.`Books_Authors` (
  `idBook` BIGINT UNSIGNED NOT NULL,
  `idAuthor` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`idAuthor`, `idBook`),
  INDEX `fk_Books_Authors_Books1_idx` (`idBook` ASC) VISIBLE,
  CONSTRAINT `fk_Books_Authors_Authors1`
    FOREIGN KEY (`idAuthor`)
    REFERENCES `booksDB`.`Authors` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Books_Authors_Books1`
    FOREIGN KEY (`idBook`)
    REFERENCES `booksDB`.`Books` (`isbn`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;


INSERT INTO `Locations` (`id`, `name`) VALUES (NULL, "Bibliothèque du salon");