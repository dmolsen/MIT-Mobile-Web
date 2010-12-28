CREATE TABLE `mobilewebosp`.`Buildings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `code` varchar(255) DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `hours` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `wifi` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `subtype` varchar(45) DEFAULT NULL,
  `campus` varchar(45) DEFAULT NULL,
  `latitude` varchar(45) DEFAULT NULL,
  `longitude` varchar(45) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `physical_address` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

CREATE TABLE `mobilewebosp`.`Pageviews_by_Device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `count` int(11) DEFAULT NULL,
  `device` varchar(255) DEFAULT NULL,
  `day` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

CREATE TABLE `mobilewebosp`.`Pageviews_by_Section` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `count` int(11) DEFAULT NULL,
  `section` varchar(255) DEFAULT NULL,
  `day` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

