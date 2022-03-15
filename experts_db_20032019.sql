# Host: localhost  (Version 5.5.24-log)
# Date: 2019-03-20 08:37:43
# Generator: MySQL-Front 5.4  (Build 1.15)

/*!40101 SET NAMES utf8 */;

#
# Structure for table "experts_temp"
#

DROP TABLE IF EXISTS `experts_temp`;
CREATE TABLE `experts_temp` (
  `expertID` int(11) NOT NULL AUTO_INCREMENT,
  `nomExpert` varchar(45) DEFAULT NULL,
  `prenomExpert` varchar(45) DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `lieuNaissance` varchar(45) DEFAULT NULL,
  `numeroCNI` decimal(10,0) DEFAULT NULL,
  `telephone` decimal(10,0) DEFAULT NULL,
  `fax` decimal(10,0) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `adresse` varchar(45) DEFAULT NULL,
  `dateDesignation` varchar(45) DEFAULT NULL,
  `domaineCompetenceID` int(11) DEFAULT NULL,
  `localisationID` int(11) DEFAULT NULL,
  PRIMARY KEY (`expertID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "experts_temp"
#


#
# Structure for table "pays"
#

DROP TABLE IF EXISTS `pays`;
CREATE TABLE `pays` (
  `paysID` int(11) NOT NULL,
  `paysName` varchar(45) DEFAULT NULL,
  `display` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`paysID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "pays"
#


#
# Structure for table "qualifications"
#

DROP TABLE IF EXISTS `qualifications`;
CREATE TABLE `qualifications` (
  `qualificationID` int(11) NOT NULL AUTO_INCREMENT,
  `qualificationName` varchar(45) DEFAULT NULL,
  `display` varchar(1) NOT NULL,
  PRIMARY KEY (`qualificationID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "qualifications"
#


#
# Structure for table "experts"
#

DROP TABLE IF EXISTS `experts`;
CREATE TABLE `experts` (
  `expertID` int(11) NOT NULL AUTO_INCREMENT,
  `nomExpert` varchar(45) DEFAULT NULL,
  `prenomExpert` varchar(45) DEFAULT NULL,
  `dateNaissance` date DEFAULT NULL,
  `lieuNaissance` varchar(45) DEFAULT NULL,
  `numeroCNI` decimal(10,0) DEFAULT NULL,
  `telephone` decimal(10,0) DEFAULT NULL,
  `fax` decimal(10,0) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `adresse` varchar(45) DEFAULT NULL,
  `dateDesignation` varchar(45) DEFAULT NULL,
  `domaineCompetenceID` int(11) DEFAULT NULL,
  `localisationID` int(11) DEFAULT NULL,
  `Qualifications_qualificationID` int(11) NOT NULL,
  `sanctionne` varchar(1) DEFAULT NULL,
  `display` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`expertID`,`Qualifications_qualificationID`),
  KEY `fk_experts_Qualifications1_idx` (`Qualifications_qualificationID`),
  CONSTRAINT `fk_experts_Qualifications1` FOREIGN KEY (`Qualifications_qualificationID`) REFERENCES `qualifications` (`qualificationID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "experts"
#


#
# Structure for table "regions"
#

DROP TABLE IF EXISTS `regions`;
CREATE TABLE `regions` (
  `regionID` int(11) NOT NULL,
  `regionName` varchar(45) DEFAULT NULL,
  `pays_paysID` int(11) NOT NULL,
  `display` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`regionID`),
  KEY `fk_regions_pays1_idx` (`pays_paysID`),
  CONSTRAINT `fk_regions_pays1` FOREIGN KEY (`pays_paysID`) REFERENCES `pays` (`paysID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "regions"
#


#
# Structure for table "localites"
#

DROP TABLE IF EXISTS `localites`;
CREATE TABLE `localites` (
  `localiteID` int(11) NOT NULL,
  `localiteName` varchar(45) DEFAULT NULL,
  `display` varchar(45) DEFAULT NULL,
  `regions_regionID` int(11) NOT NULL,
  PRIMARY KEY (`localiteID`,`regions_regionID`),
  KEY `fk_localites_regions_idx` (`regions_regionID`),
  CONSTRAINT `fk_localites_regions` FOREIGN KEY (`regions_regionID`) REFERENCES `regions` (`regionID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "localites"
#


#
# Structure for table "localisations"
#

DROP TABLE IF EXISTS `localisations`;
CREATE TABLE `localisations` (
  `expertID` int(11) NOT NULL,
  `localiteID` int(11) NOT NULL,
  `regionID` int(11) NOT NULL,
  `display` varchar(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`expertID`,`localiteID`,`regionID`),
  KEY `fk_localisations_localites1_idx` (`localiteID`,`regionID`),
  KEY `fk_localisations_experts1_idx` (`expertID`),
  CONSTRAINT `fk_experts_has_localites_experts1` FOREIGN KEY (`expertID`) REFERENCES `experts` (`expertID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_experts_has_localites_localites1` FOREIGN KEY (`localiteID`, `regionID`) REFERENCES `localites` (`localiteID`, `regions_regionID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "localisations"
#


#
# Structure for table "typedomaines"
#

DROP TABLE IF EXISTS `typedomaines`;
CREATE TABLE `typedomaines` (
  `typeDomaineID` int(11) NOT NULL AUTO_INCREMENT,
  `typeDomaineName` varchar(45) DEFAULT NULL,
  `display` varchar(1) DEFAULT NULL,
  PRIMARY KEY (`typeDomaineID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

#
# Data for table "typedomaines"
#

INSERT INTO `typedomaines` VALUES (1,'Travaux Routiers','1'),(2,'Travaux de Batiments et Equipements Collectif','1'),(3,'Autres Infrastructures','1'),(4,'Approvisionnements GÃ©nÃ©raux','1'),(5,'Services et Prestations Intellectuelles (Etud','1');

#
# Structure for table "domaine"
#

DROP TABLE IF EXISTS `domaine`;
CREATE TABLE `domaine` (
  `domaineCompetenceID` int(11) NOT NULL,
  `domaineName` varchar(45) DEFAULT NULL,
  `TypeDomaines_typeDomaineID` int(11) NOT NULL DEFAULT '0',
  `display` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`domaineCompetenceID`,`TypeDomaines_typeDomaineID`),
  KEY `fk_Domaine_TypeDomaines1_idx` (`TypeDomaines_typeDomaineID`),
  CONSTRAINT `fk_Domaine_TypeDomaines1` FOREIGN KEY (`TypeDomaines_typeDomaineID`) REFERENCES `typedomaines` (`typeDomaineID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "domaine"
#

INSERT INTO `domaine` VALUES (1,'Travaux neufs, rÃ©habilitation ou entretien;',1,'1'),(2,'Voiries e Reseaux Divers;',1,'1'),(3,'Ouvrages d\'art (pont, tunnel, digue);',1,'1'),(4,'Genie urbain;',2,'1'),(5,'GÃ©nie Civil',2,'1');

#
# Structure for table "specialisations"
#

DROP TABLE IF EXISTS `specialisations`;
CREATE TABLE `specialisations` (
  `expertID` int(11) NOT NULL,
  `domaineCompetenceID` int(11) NOT NULL,
  PRIMARY KEY (`expertID`,`domaineCompetenceID`),
  KEY `fk_specialisations_experts_idx` (`domaineCompetenceID`),
  KEY `fk_specialisations_domaines_idx` (`expertID`),
  CONSTRAINT `fk_experts_has_Domaine_competence_Domaine_competence1` FOREIGN KEY (`domaineCompetenceID`) REFERENCES `domaine` (`domaineCompetenceID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_experts_has_Domaine_competence_experts1` FOREIGN KEY (`expertID`) REFERENCES `experts` (`expertID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "specialisations"
#

