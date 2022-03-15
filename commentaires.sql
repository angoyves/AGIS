# Host: localhost  (Version: 5.5.16-log)
# Date: 2015-09-02 09:55:07
# Generator: MySQL-Front 5.3  (Build 4.175)

/*!40101 SET NAMES latin1 */;

#
# Structure for table "commentaires"
#

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE `commentaires` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `objet` varchar(100) DEFAULT NULL,
  `expediteur_id` int(11) DEFAULT NULL,
  `destinataire_id` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `notation` int(1) DEFAULT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

#
# Data for table "commentaires"
#

INSERT INTO `commentaires` VALUES (1,'dsgqjsgdqsjgd',NULL,12,'dqsdjgsfjqgsfjqsgfj',0),(2,'qdbsqjdgjqg',NULL,5,'jhggfdsjgfsj',0),(3,'dgsjhqgdj',NULL,12,'qdbkjqhdkqjshdkqhdqkj',0),(4,'qqskjhdqskh',NULL,7,'dkfhssdjhfksdh',0);
