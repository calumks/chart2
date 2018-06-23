-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 11, 2018 at 06:20 PM
-- Server version: 10.1.31-MariaDB
-- PHP Version: 7.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Create a database and use it
CREATE DATABASE chart2;
USE chart2;

--
--  Create a user to whom the views (later on) can be attributed
GRANT ALL PRIVILEGES ON *.* TO 'chartUser'@'localhost' IDENTIFIED BY 'SomeSuitablePassword999!';

--  Create a user for travis and mysql-cred.php
GRANT ALL PRIVILEGES ON *.* TO 'makeUpAUserName'@'localhost' IDENTIFIED BY 'makeUpASatisfactoryPassword';
-- --------------------------------------------------------

--
-- Table structure for table `arrangement`
--

CREATE TABLE `arrangement` (
  `arrangementID` int(11) NOT NULL,
  `songID` int(11) NOT NULL,
  `arrangerPersonID` int(11) NOT NULL,
  `isInPads` tinyint(1) NOT NULL,
  `isBackedUp` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;



-- --------------------------------------------------------

--
-- Table structure for table `arrangementCategory`
--

CREATE TABLE `arrangementCategory` (
  `arrangementID` int(11) NOT NULL,
  `categoryID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `band`
--

CREATE TABLE `band` (
  `subSectionID` int(11) NOT NULL,
  `superSectionID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `band`
--

INSERT INTO `band` (`subSectionID`, `superSectionID`) VALUES
(1, 3),
(2, 3),
(3, 8),
(4, 8),
(5, 8),
(6, 7),
(8, 7);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryID`, `name`) VALUES
(1, '1940s'),
(2, 'Glenn Miller'),
(4, 'Quick Step'),
(3, 'Swing'),
(5, 'Waltz');

-- --------------------------------------------------------

--
-- Table structure for table `confirmation`
--

CREATE TABLE `confirmation` (
  `confirmationID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `confirmationCode` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `tsbcode` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `IP` varchar(60) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `efile`
--

CREATE TABLE `efile` (
  `efileID` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `efileTypeID` int(11) NOT NULL,
  `publicationID` int(11) NOT NULL,
  `formatID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `efileLocation`
--

CREATE TABLE `efileLocation` (
  `efileID` int(11) NOT NULL,
  `locationTypeID` int(11) NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `efilePart`
--

CREATE TABLE `efilePart` (
  `efilePartID` int(11) NOT NULL,
  `efileID` int(11) NOT NULL,
  `partID` int(11) NOT NULL,
  `startPage` int(11) NOT NULL,
  `endPage` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


-- --------------------------------------------------------

--
-- Table structure for table `efileType`
--

CREATE TABLE `efileType` (
  `efileTypeID` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `efileType`
--

INSERT INTO `efileType` (`efileTypeID`, `name`) VALUES
(1, 'pdf');

-- --------------------------------------------------------

--
-- Table structure for table `gig`
--

CREATE TABLE `gig` (
  `gigID` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `gigDate` date NOT NULL,
  `isGig` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `locationType`
--

CREATE TABLE `locationType` (
  `locationTypeID` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `note`
--

CREATE TABLE `note` (
  `noteID` int(11) NOT NULL,
  `publicationID` int(11) NOT NULL,
  `noteDate` datetime DEFAULT NULL,
  `noteText` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Table structure for table `part`
--

CREATE TABLE `part` (
  `partID` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `minSectionID` int(11) DEFAULT '8'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `part`
--

INSERT INTO `part` (`partID`, `name`, `minSectionID`) VALUES
(1, 'Trumpet 1', 1),
(2, 'Trumpet 2', 1),
(3, 'Trumpet 3', 1),
(4, 'Trumpet 4', 1),
(5, 'Trombone 1', 2),
(6, 'Trombone 2', 2),
(7, 'Trombone 3', 2),
(8, 'Trombone 4', 2),
(9, 'Drums', 5),
(10, 'Guitar', 5),
(11, 'Bass', 5),
(12, 'Piano', 5),
(13, 'Alto Sax 1', 4),
(14, 'Alto Sax 2', 4),
(15, 'Tenor Sax 1', 4),
(16, 'Tenor Sax 2', 4),
(17, 'Baritone Sax', 4),
(18, 'Vocal Solo', 6),
(19, 'Vocal Soprano', 6),
(20, 'Vocal Alto', 6),
(21, 'Vocal Tenor', 6),
(22, 'Vocal Bass', 6),
(23, 'Score', 8),
(24, 'Vocal Group', 6),
(26, 'Flute', 4),
(27, 'Clarinet', 4),
(28, 'Tuba', 3),
(29, 'Synth', 5);

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `personID` int(11) NOT NULL,
  `firstName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `lastName` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `nickName` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `person`
--


--
-- Table structure for table `printList`
--

CREATE TABLE `printList` (
  `printListID` int(11) NOT NULL,
  `userID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `publication`
--

CREATE TABLE `publication` (
  `publicationID` int(11) NOT NULL,
  `arrangementID` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `requestID` int(11) NOT NULL,
  `requestIP` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `requestWhen` date DEFAULT NULL,
  `requestGet` text COLLATE utf8_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Table structure for table `section`
--

CREATE TABLE `section` (
  `sectionID` int(11) NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `section`
--

INSERT INTO `section` (`sectionID`, `name`) VALUES
(3, 'Brass'),
(8, 'Instrumental'),
(5, 'Rhythm'),
(4, 'Saxaphone'),
(2, 'Trombone'),
(1, 'Trumpet'),
(7, 'Vocal'),
(6, 'Voice');

-- --------------------------------------------------------

--
-- Table structure for table `setList2`
--

CREATE TABLE `setList2` (
  `setListID` int(10) NOT NULL,
  `arrangementID` int(11) NOT NULL,
  `gigID` int(11) NOT NULL,
  `setListOrder` decimal(11,3) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `setList2`
--


--
-- Table structure for table `song`
--

CREATE TABLE `song` (
  `songID` int(11) NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `song`
--


--
-- Table structure for table `songComposer`
--

CREATE TABLE `songComposer` (
  `songID` int(11) NOT NULL,
  `composerPersonID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `songComposer`
--


--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `md5email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `nickName` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `aesEmail` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `user`
--


--
-- Stand-in structure for view `view_arrangement`
-- (See below for the actual view)
--
CREATE TABLE `view_arrangement` (
`arrangementID` int(11)
,`arrangerFirstName` varchar(30)
,`arrangerLastName` varchar(30)
,`name` varchar(100)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_arrangementCategory`
-- (See below for the actual view)
--
CREATE TABLE `view_arrangementCategory` (
`arrangerLastName` varchar(30)
,`songName` varchar(100)
,`categoryName` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_band`
-- (See below for the actual view)
--
CREATE TABLE `view_band` (
`Sub` varchar(30)
,`Super` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_efile`
-- (See below for the actual view)
--
CREATE TABLE `view_efile` (
`formatID` int(11)
,`efileID` int(11)
,`typeName` varchar(30)
,`fileName` varchar(255)
,`arrangementID` int(11)
,`publicationID` int(11)
,`arrangerFirstName` varchar(30)
,`arrangerLastName` varchar(30)
,`name` varchar(100)
,`description` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_efilePageCount`
-- (See below for the actual view)
--
CREATE TABLE `view_efilePageCount` (
`countPages` decimal(34,0)
,`efileID` int(11)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_efilePages`
-- (See below for the actual view)
--
CREATE TABLE `view_efilePages` (
`efileID` int(11)
,`countPages` decimal(34,0)
,`name` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_efilePart`
-- (See below for the actual view)
--
CREATE TABLE `view_efilePart` (
`arrangementid` int(11)
,`formatID` int(11)
,`efileID` int(11)
,`partID` int(11)
,`songName` varchar(100)
,`partName` varchar(30)
,`startPage` int(11)
,`endPage` int(11)
,`fileName` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_efilePartSetList2`
-- (See below for the actual view)
--
CREATE TABLE `view_efilePartSetList2` (
`fileName` varchar(255)
,`startPage` int(11)
,`endPage` int(11)
,`formatID` int(11)
,`partName` varchar(30)
,`arrangementid` int(11)
,`gigID` int(11)
,`setListOrder` decimal(11,3)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_note`
-- (See below for the actual view)
--
CREATE TABLE `view_note` (
`noteID` int(11)
,`arrangerLastName` varchar(30)
,`name` varchar(100)
,`description` varchar(255)
,`noteText` text
,`noteDate` datetime
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_part`
-- (See below for the actual view)
--
CREATE TABLE `view_part` (
`Sub` varchar(30)
,`Super` varchar(30)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_popular`
-- (See below for the actual view)
--
CREATE TABLE `view_popular` (
`arrangementID` int(11)
,`name` varchar(100)
,`countPlays` bigint(21)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_publication`
-- (See below for the actual view)
--
CREATE TABLE `view_publication` (
`arrangementID` int(11)
,`publicationID` int(11)
,`arrangerFirstName` varchar(30)
,`arrangerLastName` varchar(30)
,`name` varchar(100)
,`description` varchar(255)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_songComposer`
-- (See below for the actual view)
--
CREATE TABLE `view_songComposer` (
`personID` int(11)
,`firstName` varchar(30)
,`lastName` varchar(30)
,`nickName` varchar(30)
,`songID` int(11)
,`name` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure for view `view_arrangement`
--
DROP TABLE IF EXISTS `view_arrangement`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_arrangement`  AS  select `a`.`arrangementID` AS `arrangementID`,`b1`.`firstName` AS `arrangerFirstName`,`b1`.`lastName` AS `arrangerLastName`,`b2`.`name` AS `name` from ((`person` `b1` join `song` `b2`) join `arrangement` `a`) where ((`b1`.`personID` = `a`.`arrangerPersonID`) and (`b2`.`songID` = `a`.`songID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_arrangementCategory`
--
DROP TABLE IF EXISTS `view_arrangementCategory`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_arrangementCategory`  AS  select `b1`.`lastName` AS `arrangerLastName`,`b2`.`name` AS `songName`,`c`.`name` AS `categoryName` from ((((`person` `b1` join `song` `b2`) join `arrangement` `a`) join `category` `c`) join `arrangementCategory` `ac`) where ((`b1`.`personID` = `a`.`arrangerPersonID`) and (`b2`.`songID` = `a`.`songID`) and (`a`.`arrangementID` = `ac`.`arrangementID`) and (`c`.`categoryID` = `ac`.`categoryID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_band`
--
DROP TABLE IF EXISTS `view_band`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_band`  AS  select `b1`.`name` AS `Sub`,`b2`.`name` AS `Super` from ((`section` `b1` join `section` `b2`) join `band` `b`) where ((`b`.`subSectionID` = `b1`.`sectionID`) and (`b`.`superSectionID` = `b2`.`sectionID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_efile`
--
DROP TABLE IF EXISTS `view_efile`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_efile`  AS  select `b2`.`formatID` AS `formatID`,`b2`.`efileID` AS `efileID`,`b1`.`name` AS `typeName`,`b2`.`name` AS `fileName`,`p`.`arrangementID` AS `arrangementID`,`p`.`publicationID` AS `publicationID`,`p`.`arrangerFirstName` AS `arrangerFirstName`,`p`.`arrangerLastName` AS `arrangerLastName`,`p`.`name` AS `name`,`p`.`description` AS `description` from ((`efileType` `b1` join `efile` `b2`) join `view_publication` `p`) where ((`b1`.`efileTypeID` = `b2`.`efileTypeID`) and (`b2`.`publicationID` = `p`.`publicationID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_efilePageCount`
--
DROP TABLE IF EXISTS `view_efilePageCount`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_efilePageCount`  AS  select sum(((`P`.`endPage` - `P`.`startPage`) + 1)) AS `countPages`,`P`.`efileID` AS `efileID` from `efilePart` `P` group by `P`.`efileID` ;

-- --------------------------------------------------------

--
-- Structure for view `view_efilePages`
--
DROP TABLE IF EXISTS `view_efilePages`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_efilePages`  AS  select `E`.`efileID` AS `efileID`,`C`.`countPages` AS `countPages`,`E`.`name` AS `name` from (`efile` `E` left join `view_efilePageCount` `C` on((`E`.`efileID` = `C`.`efileID`))) ;

-- --------------------------------------------------------

--
-- Structure for view `view_efilePart`
--
DROP TABLE IF EXISTS `view_efilePart`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_efilePart`  AS  select `v`.`arrangementID` AS `arrangementid`,`v`.`formatID` AS `formatID`,`v`.`efileID` AS `efileID`,`t`.`partID` AS `partID`,`v`.`name` AS `songName`,`t`.`name` AS `partName`,`i`.`startPage` AS `startPage`,`i`.`endPage` AS `endPage`,`v`.`fileName` AS `fileName` from ((`efilePart` `i` join `view_efile` `v`) join `part` `t`) where ((`v`.`efileID` = `i`.`efileID`) and (`i`.`partID` = `t`.`partID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_efilePartSetList2`
--
DROP TABLE IF EXISTS `view_efilePartSetList2`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_efilePartSetList2`  AS  select `V`.`fileName` AS `fileName`,`V`.`startPage` AS `startPage`,`V`.`endPage` AS `endPage`,`V`.`formatID` AS `formatID`,`V`.`partName` AS `partName`,`S`.`arrangementID` AS `arrangementid`,`S`.`gigID` AS `gigID`,`S`.`setListOrder` AS `setListOrder` from (`view_efilePart` `V` join `setList2` `S`) where (`V`.`arrangementid` = `S`.`arrangementID`) order by `S`.`gigID`,`S`.`setListOrder`,`V`.`partName` ;

-- --------------------------------------------------------

--
-- Structure for view `view_note`
--
DROP TABLE IF EXISTS `view_note`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_note`  AS  select `n`.`noteID` AS `noteID`,`b1`.`lastName` AS `arrangerLastName`,`b2`.`name` AS `name`,`p`.`description` AS `description`,`n`.`noteText` AS `noteText`,`n`.`noteDate` AS `noteDate` from ((((`person` `b1` join `song` `b2`) join `publication` `p`) join `arrangement` `a`) join `note` `n`) where ((`b1`.`personID` = `a`.`arrangerPersonID`) and (`b2`.`songID` = `a`.`songID`) and (`a`.`arrangementID` = `p`.`arrangementID`) and (`n`.`publicationID` = `p`.`publicationID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_part`
--
DROP TABLE IF EXISTS `view_part`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_part`  AS  select `b1`.`name` AS `Sub`,`b2`.`name` AS `Super` from (`part` `b1` join `section` `b2`) where (`b1`.`minSectionID` = `b2`.`sectionID`) ;

-- --------------------------------------------------------

--
-- Structure for view `view_popular`
--
DROP TABLE IF EXISTS `view_popular`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_popular`  AS  select `A`.`arrangementID` AS `arrangementID`,`A`.`name` AS `name`,count(0) AS `countPlays` from ((`view_arrangement` `A` join `setList2` `S` on((`A`.`arrangementID` = `S`.`arrangementID`))) join `gig` `G` on((`G`.`gigID` = `S`.`gigID`))) where (`G`.`isGig` <> 0) group by `A`.`arrangementID` order by count(0) desc,`A`.`name` ;

-- --------------------------------------------------------

--
-- Structure for view `view_publication`
--
DROP TABLE IF EXISTS `view_publication`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_publication`  AS  select `a`.`arrangementID` AS `arrangementID`,`p`.`publicationID` AS `publicationID`,`b1`.`firstName` AS `arrangerFirstName`,`b1`.`lastName` AS `arrangerLastName`,`b2`.`name` AS `name`,`p`.`description` AS `description` from (((`person` `b1` join `song` `b2`) join `publication` `p`) join `arrangement` `a`) where ((`b1`.`personID` = `a`.`arrangerPersonID`) and (`b2`.`songID` = `a`.`songID`) and (`a`.`arrangementID` = `p`.`arrangementID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_songComposer`
--
DROP TABLE IF EXISTS `view_songComposer`;

CREATE ALGORITHM=UNDEFINED DEFINER=`chartUser`@`localhost` SQL SECURITY DEFINER VIEW `view_songComposer`  AS  select `b1`.`personID` AS `personID`,`b1`.`firstName` AS `firstName`,`b1`.`lastName` AS `lastName`,`b1`.`nickName` AS `nickName`,`b2`.`songID` AS `songID`,`b2`.`name` AS `name` from ((`person` `b1` join `song` `b2`) join `songComposer` `SC`) where ((`b1`.`personID` = `SC`.`composerPersonID`) and (`b2`.`songID` = `SC`.`songID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `arrangement`
--
ALTER TABLE `arrangement`
  ADD PRIMARY KEY (`arrangementID`),
  ADD UNIQUE KEY `songID` (`songID`,`arrangerPersonID`),
  ADD KEY `arrangerPersonID` (`arrangerPersonID`);

--
-- Indexes for table `arrangementCategory`
--
ALTER TABLE `arrangementCategory`
  ADD PRIMARY KEY (`arrangementID`,`categoryID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `band`
--
ALTER TABLE `band`
  ADD PRIMARY KEY (`subSectionID`,`superSectionID`),
  ADD KEY `superSectionID` (`superSectionID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `confirmation`
--
ALTER TABLE `confirmation`
  ADD PRIMARY KEY (`confirmationID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `efile`
--
ALTER TABLE `efile`
  ADD PRIMARY KEY (`efileID`),
  ADD KEY `efileTypeID` (`efileTypeID`),
  ADD KEY `publicationID` (`publicationID`);

--
-- Indexes for table `efileLocation`
--
ALTER TABLE `efileLocation`
  ADD UNIQUE KEY `location` (`location`),
  ADD KEY `efileID` (`efileID`),
  ADD KEY `locationTypeID` (`locationTypeID`);

--
-- Indexes for table `efilePart`
--
ALTER TABLE `efilePart`
  ADD PRIMARY KEY (`efilePartID`),
  ADD KEY `efileID` (`efileID`),
  ADD KEY `partID` (`partID`);

--
-- Indexes for table `efileType`
--
ALTER TABLE `efileType`
  ADD PRIMARY KEY (`efileTypeID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `gig`
--
ALTER TABLE `gig`
  ADD PRIMARY KEY (`gigID`),
  ADD UNIQUE KEY `name` (`name`,`gigDate`);

--
-- Indexes for table `locationType`
--
ALTER TABLE `locationType`
  ADD PRIMARY KEY (`locationTypeID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `note`
--
ALTER TABLE `note`
  ADD PRIMARY KEY (`noteID`),
  ADD KEY `publicationID` (`publicationID`);

--
-- Indexes for table `part`
--
ALTER TABLE `part`
  ADD PRIMARY KEY (`partID`),
  ADD UNIQUE KEY `name` (`name`),
  ADD KEY `minSectionID` (`minSectionID`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`personID`),
  ADD UNIQUE KEY `firstName` (`firstName`,`lastName`,`nickName`);

--
-- Indexes for table `printList`
--
ALTER TABLE `printList`
  ADD PRIMARY KEY (`printListID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `publication`
--
ALTER TABLE `publication`
  ADD PRIMARY KEY (`publicationID`),
  ADD UNIQUE KEY `description` (`description`,`arrangementID`),
  ADD KEY `arrangementID` (`arrangementID`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD PRIMARY KEY (`requestID`);

--
-- Indexes for table `section`
--
ALTER TABLE `section`
  ADD PRIMARY KEY (`sectionID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `setList2`
--
ALTER TABLE `setList2`
  ADD PRIMARY KEY (`setListID`),
  ADD UNIQUE KEY `gigOrder` (`gigID`,`setListOrder`);

--
-- Indexes for table `song`
--
ALTER TABLE `song`
  ADD PRIMARY KEY (`songID`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `songComposer`
--
ALTER TABLE `songComposer`
  ADD PRIMARY KEY (`songID`,`composerPersonID`),
  ADD KEY `composerPersonID` (`composerPersonID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `md5email` (`md5email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `arrangement`
--
ALTER TABLE `arrangement`
  MODIFY `arrangementID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=320;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `confirmation`
--
ALTER TABLE `confirmation`
  MODIFY `confirmationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `efile`
--
ALTER TABLE `efile`
  MODIFY `efileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `efilePart`
--
ALTER TABLE `efilePart`
  MODIFY `efilePartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1468;

--
-- AUTO_INCREMENT for table `efileType`
--
ALTER TABLE `efileType`
  MODIFY `efileTypeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `gig`
--
ALTER TABLE `gig`
  MODIFY `gigID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `locationType`
--
ALTER TABLE `locationType`
  MODIFY `locationTypeID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `note`
--
ALTER TABLE `note`
  MODIFY `noteID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `part`
--
ALTER TABLE `part`
  MODIFY `partID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `personID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=979;

--
-- AUTO_INCREMENT for table `printList`
--
ALTER TABLE `printList`
  MODIFY `printListID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `publication`
--
ALTER TABLE `publication`
  MODIFY `publicationID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `request`
--
ALTER TABLE `request`
  MODIFY `requestID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=837;

--
-- AUTO_INCREMENT for table `section`
--
ALTER TABLE `section`
  MODIFY `sectionID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `setList2`
--
ALTER TABLE `setList2`
  MODIFY `setListID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=696;

--
-- AUTO_INCREMENT for table `song`
--
ALTER TABLE `song`
  MODIFY `songID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=414;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arrangement`
--
ALTER TABLE `arrangement`
  ADD CONSTRAINT `arrangement_ibfk_1` FOREIGN KEY (`songID`) REFERENCES `song` (`songID`),
  ADD CONSTRAINT `arrangement_ibfk_2` FOREIGN KEY (`arrangerPersonID`) REFERENCES `person` (`personID`);

--
-- Constraints for table `arrangementCategory`
--
ALTER TABLE `arrangementCategory`
  ADD CONSTRAINT `arrangementCategory_ibfk_1` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`),
  ADD CONSTRAINT `arrangementCategory_ibfk_2` FOREIGN KEY (`arrangementID`) REFERENCES `arrangement` (`arrangementID`);

--
-- Constraints for table `band`
--
ALTER TABLE `band`
  ADD CONSTRAINT `band_ibfk_1` FOREIGN KEY (`subSectionID`) REFERENCES `section` (`sectionID`),
  ADD CONSTRAINT `band_ibfk_2` FOREIGN KEY (`superSectionID`) REFERENCES `section` (`sectionID`);

--
-- Constraints for table `confirmation`
--
ALTER TABLE `confirmation`
  ADD CONSTRAINT `confirmation_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `efile`
--
ALTER TABLE `efile`
  ADD CONSTRAINT `efile_ibfk_1` FOREIGN KEY (`efileTypeID`) REFERENCES `efileType` (`efileTypeID`),
  ADD CONSTRAINT `efile_ibfk_2` FOREIGN KEY (`publicationID`) REFERENCES `publication` (`publicationID`);

--
-- Constraints for table `efileLocation`
--
ALTER TABLE `efileLocation`
  ADD CONSTRAINT `efileLocation_ibfk_1` FOREIGN KEY (`efileID`) REFERENCES `efile` (`efileID`),
  ADD CONSTRAINT `efileLocation_ibfk_2` FOREIGN KEY (`locationTypeID`) REFERENCES `locationType` (`locationTypeID`);

--
-- Constraints for table `efilePart`
--
ALTER TABLE `efilePart`
  ADD CONSTRAINT `efilePart_ibfk_1` FOREIGN KEY (`efileID`) REFERENCES `efile` (`efileID`),
  ADD CONSTRAINT `efilePart_ibfk_2` FOREIGN KEY (`partID`) REFERENCES `part` (`partID`);

--
-- Constraints for table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`publicationID`) REFERENCES `publication` (`publicationID`);

--
-- Constraints for table `part`
--
ALTER TABLE `part`
  ADD CONSTRAINT `part_ibfk_1` FOREIGN KEY (`minSectionID`) REFERENCES `section` (`sectionID`);

--
-- Constraints for table `printList`
--
ALTER TABLE `printList`
  ADD CONSTRAINT `printList_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `publication`
--
ALTER TABLE `publication`
  ADD CONSTRAINT `publication_ibfk_1` FOREIGN KEY (`arrangementID`) REFERENCES `arrangement` (`arrangementID`);

--
-- Constraints for table `songComposer`
--
ALTER TABLE `songComposer`
  ADD CONSTRAINT `songComposer_ibfk_1` FOREIGN KEY (`songID`) REFERENCES `song` (`songID`),
  ADD CONSTRAINT `songComposer_ibfk_2` FOREIGN KEY (`composerPersonID`) REFERENCES `person` (`personID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
