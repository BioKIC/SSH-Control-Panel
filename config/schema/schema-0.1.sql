CREATE TABLE `portal` (
  `portalID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `tcnLink` varchar(250) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `geoScope1` varchar(45) DEFAULT NULL,
  `geoScope2` varchar(45) DEFAULT NULL,
  `taxonScope1` varchar(45) DEFAULT NULL,
  `taxonScope2` varchar(45) DEFAULT NULL,
  `projectLead` varchar(45) DEFAULT NULL,
  `projectLeadEmail` varchar(45) DEFAULT NULL,
  `projectUrl` varchar(250) DEFAULT NULL,
  `initialTimestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`portalID`),
  UNIQUE KEY `UQ_portal_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `frontend` (
  `frontendID` int(11) NOT NULL AUTO_INCREMENT,
  `portalID` int(11) DEFAULT NULL,
  `name` varchar(45) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL,
  `category` varchar(45) DEFAULT NULL,
  `scope` varchar(45) DEFAULT NULL,
  `managers` varchar(45) DEFAULT NULL,
  `managerEmail` varchar(45) DEFAULT NULL,
  `primaryLead` varchar(45) DEFAULT NULL,
  `primaryLeadEmail` varchar(45) DEFAULT NULL,
  `portalUrl` varchar(250) DEFAULT NULL,
  `initialTimestamp` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`frontendID`),
  UNIQUE KEY `UQ_frontend_name` (`name`),
  KEY `FK_frontend_portalID_idx` (`portalID`),
  CONSTRAINT `FK_frontend_portalID` FOREIGN KEY (`portalID`) REFERENCES `portal` (`portalID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

