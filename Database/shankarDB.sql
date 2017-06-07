-- phpMyAdmin SQL Dump
-- version 4.0.10.18
-- https://www.phpmyadmin.net
--
-- Host: localhost:3306
-- Generation Time: Jun 07, 2017 at 01:14 AM
-- Server version: 5.6.35-cll-lve
-- PHP Version: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `shankarDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE IF NOT EXISTS `brands` (
  `BrandID` int(11) NOT NULL AUTO_INCREMENT,
  `BrandName` varchar(50) NOT NULL,
  PRIMARY KEY (`BrandID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`BrandID`, `BrandName`) VALUES
(1, 'PIRELLI'),
(2, 'APOLLO'),
(3, 'MICHELIN'),
(4, 'GOODYEAR'),
(5, 'YOKOHAMA'),
(6, 'ACHILLES'),
(7, 'MAXIS'),
(8, 'CEAT'),
(9, 'FALKEN'),
(10, 'MRF'),
(11, 'AMARON'),
(12, 'MAT'),
(13, 'SHALDAN'),
(14, 'AVIS'),
(15, 'BRIDGESTONE'),
(16, 'MAXPIDER'),
(17, 'PY');

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE IF NOT EXISTS `location` (
  `LocationID` int(11) NOT NULL AUTO_INCREMENT,
  `LocationName` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`LocationID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `location`
--

INSERT INTO `location` (`LocationID`, `LocationName`) VALUES
(1, 'Main Office BL circle'),
(2, 'Sub office Marathalli');

-- --------------------------------------------------------

--
-- Table structure for table `nonbillable`
--

CREATE TABLE IF NOT EXISTS `nonbillable` (
  `RecordID` int(11) NOT NULL AUTO_INCREMENT,
  `RecordDate` datetime NOT NULL,
  `Perticulars` varchar(255) CHARACTER SET utf8 NOT NULL,
  `AmountPaid` decimal(19,2) NOT NULL DEFAULT '0.00',
  `Notes` varchar(255) CHARACTER SET ucs2 NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`RecordID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `nonbillable`
--

INSERT INTO `nonbillable` (`RecordID`, `RecordDate`, `Perticulars`, `AmountPaid`, `Notes`, `UserID`) VALUES
(1, '2017-05-25 13:21:00', 'Puncture', '100.00', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `productinvetory`
--

CREATE TABLE IF NOT EXISTS `productinvetory` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `BrandID` int(11) NOT NULL,
  `ProductSize` varchar(50) CHARACTER SET utf8 NOT NULL,
  `ProductPattern` varchar(50) NOT NULL,
  `SupplierID` int(11) NOT NULL,
  `ProductTypeID` int(11) NOT NULL,
  `CostPrice` decimal(19,2) NOT NULL DEFAULT '0.00',
  `MinSellPrice` decimal(19,2) NOT NULL DEFAULT '0.00',
  `MaxSellPrice` decimal(19,2) NOT NULL DEFAULT '0.00',
  `ProductNotes` varchar(255) CHARACTER SET utf8 NOT NULL,
  `MinStockAlert` int(1) NOT NULL,
  `DateOfEntry` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `LastModified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ProductID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE IF NOT EXISTS `products` (
  `ProductID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceID` int(11) NOT NULL,
  `ProductType` int(11) NOT NULL,
  `ProductSize` varchar(50) CHARACTER SET utf8 NOT NULL,
  `ProductBrand` varchar(50) CHARACTER SET utf8 NOT NULL,
  `ProductBrandID` int(11) NOT NULL,
  `Pattern` varchar(30) CHARACTER SET utf8 NOT NULL,
  `ProductQty` int(11) NOT NULL,
  `UnitPrice` decimal(19,2) NOT NULL DEFAULT '0.00',
  `Amount` decimal(19,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`ProductID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=130 ;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `InvoiceID`, `ProductType`, `ProductSize`, `ProductBrand`, `ProductBrandID`, `Pattern`, `ProductQty`, `UnitPrice`, `Amount`) VALUES
(1, 1, 1, '175/60 R13', 'YOKOHAMA', 0, 'Earth one', 5, '2760.00', '13800.00'),
(2, 1, 1, '205/65 R15', 'YOKOHAMA', 0, 'Earth one', 8, '4620.00', '36960.00'),
(3, 2, 5, 'B-14', 'AMARON', 0, 'DIN45', 1, '3701.00', '3701.00'),
(4, 3, 1, '185/60 R15', 'GOODYEAR', 0, 'DURAPLUS', 8, '3106.35', '24850.80'),
(5, 3, 1, '175/70 R13', 'GOODYEAR', 0, 'DURAPLUS', 5, '3007.87', '15039.35'),
(6, 3, 1, '175/70 R13', 'GOODYEAR', 0, 'DURAPLUS', 5, '261.30', '1306.50'),
(7, 4, 1, '185/60 R15', 'YOKOHAMA', 0, 'EARTH ONE', 4, '3900.00', '15600.00'),
(8, 4, 1, '195/55 R15', 'YOKOHAMA', 0, 'S DRIVE', 1, '4232.00', '4232.00'),
(9, 5, 1, '145/80 R12', 'GOODYEAR', 0, 'HI-MILLER', 10, '1909.05', '19090.50'),
(10, 5, 1, '185/70 R14', 'GOODYEAR', 0, 'TRIPLEMAX', 6, '3339.37', '20036.22'),
(11, 5, 1, '185/65 R15', 'GOODYEAR', 0, 'DURAPLUS', 2, '3902.92', '7805.84'),
(12, 6, 6, 'Air Freshener', 'Shaldan', 0, 'Lemon', 12, '174.67', '2096.04'),
(13, 6, 6, 'Air Freshener', 'Shaldan', 0, 'Squash', 12, '174.67', '2096.04'),
(14, 7, 1, '245/45 R17', 'MICHELIN', 0, 'PRECEDA', 1, '14061.14', '14061.14'),
(15, 8, 1, '155/70 R13', 'YOKOHAMA', 0, 'EARTH ONE', 4, '2625.00', '10500.00'),
(16, 8, 1, '175/70 R13', 'YOKOHAMA', 0, 'EARTH ONE', 4, '3245.00', '12980.00'),
(17, 8, 1, '185/70 R14', 'YOKOHAMA', 0, 'EARTH ONE', 2, '3418.00', '6836.00'),
(18, 8, 1, '205/65 R15', 'YOKOHAMA', 0, 'EARTH ONE', 4, '4620.00', '18480.00'),
(19, 9, 1, '235/60 R18', 'GOODYEAR', 0, 'AO', 4, '16211.32', '64845.28'),
(20, 9, 1, '165/65 R14', 'GOODYEAR', 0, 'DURAPLUS', 4, '3114.15', '12456.60'),
(21, 10, 1, '205/65 R15', 'GOODYEAR', 0, 'DURAPLUS', 6, '4435.27', '26611.62'),
(22, 11, 1, '225/70 R17', 'MICHELIN', 0, 'PRIMACY', 1, '9956.34', '9956.34'),
(23, 12, 1, '255/65 R16', 'YOKOHAMA', 0, 'G-LANDER AT', 4, '7703.00', '30812.00'),
(24, 12, 1, '165/80 R14', 'YOKOHAMA', 0, 'EARTH ONE', 1, '2700.00', '2700.00'),
(25, 13, 1, '100/90 R10', 'CEAT', 0, 'TL', 6, '811.97', '4871.82'),
(26, 13, 1, '100/90 R10', 'CEAT', 0, 'TT', 3, '699.71', '2099.14'),
(27, 13, 1, '100/90 R17', 'CEAT', 0, 'TT', 1, '1222.76', '1222.76'),
(28, 13, 1, '100/90 R17', 'CEAT', 0, 'BR', 1, '193.54', '193.54'),
(29, 13, 1, '275 R18', 'CEAT', 0, 'SECURA ZOOM', 2, '890.27', '1780.54'),
(30, 13, 1, '275/250 R18', 'CEAT', 0, 'LOOSE', 2, '173.69', '347.38'),
(31, 14, 1, '185/65 R14', 'YOKOHAMA', 0, 'EARTH ONE', 4, '3491.00', '13964.00'),
(32, 15, 6, '3D MAXPIDER MAT', 'MAT', 0, 'NEW INNOVA', 1, '5297.00', '5297.00'),
(33, 16, 2, '1000 R20', 'MICHELIN', 0, 'TT', 4, '16808.29', '67233.16'),
(34, 16, 2, '1000 R20', 'MICHELIN', 0, 'RADIAL TUBE', 4, '1600.00', '6400.00'),
(35, 16, 6, '1000 R20', 'MICHELIN', 0, 'FLAPS', 4, '500.00', '2000.00'),
(36, 16, 6, '1000 R20', 'MRF', 0, 'SM', 2, '11138.42', '22276.84'),
(37, 16, 2, '1000 R20', 'MRF', 0, 'TUBES', 2, '1250.00', '2500.00'),
(38, 16, 6, '1000 R20', 'MRF', 0, 'FLAPS', 2, '450.00', '900.00'),
(39, 17, 5, '42B20R AMARON', 'AMARON', 0, 'BATTERY', 1, '3074.00', '3074.00'),
(40, 18, 1, '205/55 R16', 'GOODYEAR', 0, 'NCT', 1, '6593.89', '6593.89'),
(41, 19, 1, '265/65 R17', 'YOKOHAMA', 0, 'G-LANDER AT', 4, '9090.00', '36360.00'),
(42, 19, 1, '185/70 R14', 'YOKOHAMA', 0, 'EARTH ONE', 4, '3418.00', '13672.00'),
(43, 20, 2, '2.75/3.00 R18', 'AVIS', 0, 'TUBE', 5, '140.00', '700.00'),
(44, 20, 2, '145/80 R12', 'AVIS', 0, 'TUBE', 3, '218.00', '654.00'),
(45, 20, 2, '155/70 R13', 'AVIS', 0, 'TUBE', 2, '230.00', '460.00'),
(46, 20, 2, '195/205 R15', 'AVIS', 0, 'TUBE', 2, '358.00', '716.00'),
(47, 21, 1, '205/65 R15', 'YOKOHAMA', 0, 'EARTH ONE', 4, '4620.00', '18480.00'),
(48, 21, 1, '245/70 R16', 'YOKOHAMA', 0, 'G-LANDER AT', 2, '6939.00', '13878.00'),
(49, 22, 1, '185/70 R14', 'MICHELIN', 0, 'TL', 4, '4104.81', '16419.24'),
(50, 23, 1, '195/65 R15', 'MICHELIN', 0, 'PRIMACY', 4, '5334.50', '21338.00'),
(51, 24, 1, '165/80 R14', 'YOKOHAMA', 0, 'EARTH ONE', 4, '2700.00', '10800.00'),
(52, 25, 1, '155/65 R13', 'YOKOHAMA', 0, 'EARTH ONE', 5, '2552.00', '12760.00'),
(53, 25, 1, '165/80 R14', 'YOKOHAMA', 0, 'EARTH ONE', 1, '2700.00', '2700.00'),
(54, 25, 1, '185/70 R14', 'YOKOHAMA', 0, 'EARTH ONE', 5, '3418.00', '17090.00'),
(55, 25, 1, '195/60 R15', 'YOKOHAMA', 0, 'EARTH ONE', 5, '4305.00', '21525.00'),
(56, 26, 6, '3D MAXPIDER MAT', 'MAT', 0, 'INNOVA', 1, '5297.00', '5297.00'),
(57, 27, 1, '195/55 R16', 'GOODYEAR', 0, 'TRIPLEMAX', 2, '5138.25', '10276.50'),
(58, 27, 1, '205/65 R15', 'GOODYEAR', 0, 'DURAPLUS', 4, '4435.27', '17741.08'),
(59, 27, 1, '185/65 R15', 'GOODYEAR', 0, 'DURAPLUS', 2, '3902.92', '7805.84'),
(60, 28, 1, '145/80 R12', 'GOODYEAR', 0, 'HI-MILLER', 10, '1909.05', '19090.50'),
(61, 28, 1, '185/70 R14', 'GOODYEAR', 0, 'TRIPLEMAX', 5, '3339.37', '16696.85'),
(62, 28, 1, '185/65 R14', 'GOODYEAR', 0, 'TRIPLEMAX', 5, '3615.30', '18076.50'),
(63, 28, 1, '165/65 R14', 'GOODYEAR', 0, 'DP-H1', 5, '3114.15', '15570.75'),
(64, 29, 1, '215/60 R16', 'MICHELIN', 0, 'PRIMACY', 1, '7772.93', '7772.93'),
(65, 30, 1, '215/60 R16', 'MICHELIN', 0, 'PRIMACY', 3, '7542.36', '22627.08'),
(66, 31, 1, '155/80 R13', 'YOKOHAMA', 0, 'EARTH ONE', 4, '2689.00', '10756.00'),
(67, 32, 1, '175/70 R13', 'GOODYEAR', 0, 'DURAPLUS', 5, '3249.67', '16248.35'),
(68, 32, 1, '175/70 R14', 'GOODYEAR', 0, 'DURAPLUS', 4, '3033.22', '12132.88'),
(69, 32, 1, '165/65 R14', 'GOODYEAR', 0, 'DP-H1', 5, '3114.15', '15570.75'),
(70, 32, 1, '175/65 R14', 'GOODYEAR', 0, 'DURAPLUS', 4, '3401.77', '13607.08'),
(71, 32, 1, '185/60 R15', 'GOODYEAR', 0, 'DP-V1', 4, '3106.35', '12425.40'),
(72, 34, 1, '185/65 R15', 'GOODYEAR', 0, 'DURAPLUS', 5, '3902.92', '19514.60'),
(73, 34, 1, '195/60 R15', 'GOODYEAR', 0, 'TRIPLEMAX', 4, '4532.77', '18131.08'),
(74, 34, 1, '195/65 R15', 'GOODYEAR', 0, 'TRIPLEMAX', 4, '4419.67', '17678.68'),
(75, 34, 1, '185/70 R14', 'GOODYEAR', 0, 'TRIPLEMAX', 5, '3339.37', '16696.85'),
(76, 34, 1, '165/80 R14', 'GOODYEAR', 0, 'DURAPLUS', 4, '2642.25', '10569.00'),
(77, 35, 1, '155/80 R13', 'GOODYEAR', 0, 'DURAPLUS', 1, '2612.02', '2612.02'),
(79, 36, 1, '245/45 R17', 'Good Year', 0, 'Efficient Grip', 4, '10908.00', '43632.00'),
(80, 37, 1, '215/55 R17', 'Bridgestone', 0, 'Turanza', 1, '9540.00', '9540.00'),
(81, 38, 1, '205/65 R15', 'Yokohama', 0, 'Earth One', 4, '4620.00', '18480.00'),
(82, 39, 1, '205/65 R15', 'Yokohama', 0, 'Earth One', 4, '4620.00', '18480.00'),
(83, 40, 4, '15', 'PY', 0, 'PY1006', 4, '4522.00', '18088.00'),
(84, 41, 1, '235/65 R17', 'Good Year', 0, 'Wrangler', 2, '6550.00', '13100.00'),
(85, 42, 1, '700/00 R15', 'Apollo', 0, 'Duramile', 2, '5825.54', '11651.08'),
(86, 42, 2, '700 R15', 'Apollo', 0, 'Tubes', 2, '550.00', '1100.00'),
(87, 43, 1, '265/65 R17', 'Good Year', 0, 'Wrangler', 4, '10671.00', '42684.00'),
(88, 43, 1, '205/65 R15', 'Good Year', 0, 'Assurance Dura Plus', 6, '5220.00', '31320.00'),
(89, 43, 1, '195/65 R15', 'Good Year', 0, 'Assurance', 1, '4553.00', '4553.00'),
(90, 43, 1, '195/55 R16', 'Good Year', 0, 'Assurance Triple Max', 2, '5944.00', '11888.00'),
(91, 44, 2, '195/205 R15', 'Avis', 0, 'Butyl', 3, '358.00', '1074.00'),
(92, 44, 2, '155/70 R13', 'Avis', 0, 'Butyl', 4, '230.00', '920.00'),
(93, 44, 2, '145/70 R13', 'Avis', 0, 'Butyl', 3, '218.00', '654.00'),
(94, 45, 1, '195/60 R15', 'Yokohama', 0, 'Earth One', 4, '4095.00', '16380.00'),
(95, 46, 1, '205/55 R15', 'Yokohama', 0, 'S -Drive', 4, '4770.00', '19080.00'),
(96, 47, 1, '195/70 R14', 'Yokohama', 0, 'Earth One', 2, '3946.00', '7892.00'),
(97, 48, 1, '155/65 R14', 'Good Year', 0, 'GT-3', 4, '2388.65', '9554.60'),
(98, 49, 1, '205/55 R16', 'Good Year', 0, 'NCT-5', 2, '6558.96', '13117.92'),
(99, 50, 1, '205/60 R16', 'Good Year', 0, 'Assurance', 2, '5126.64', '10253.28'),
(100, 51, 1, '225/55 R16', 'Good Year', 0, 'Primacy 3ST', 4, '9362.45', '37449.80'),
(101, 52, 1, '175/65 R15', 'Bridgestone', 0, 'B-250', 1, '4446.00', '4446.00'),
(102, 53, 1, '185/70 R14', 'Good Year', 0, 'Assurance Triple Max', 2, '2969.43', '5938.86'),
(103, 53, 1, '185/65 R15', 'Good Year', 0, 'Assurance Triple Max', 2, '3764.19', '7528.38'),
(104, 54, 1, '165/70 R14', 'Goodyear', 0, 'DP-M1', 4, '2572.00', '10288.00'),
(105, 55, 1, '185/65 R14', 'Goodyear', 0, 'Assurance Triple Max', 5, '3745.00', '18725.00'),
(106, 56, 1, '185/70 R14', 'Goodyear', 0, 'Assurance Triplemax', 5, '3845.00', '19225.00'),
(107, 57, 1, '205/60 R16', 'Goodyear', 0, 'Assurance', 5, '5450.00', '27250.00'),
(108, 58, 1, '165/65 R14', 'Goodyear', 0, 'DP-H1', 5, '3810.00', '19050.00'),
(109, 59, 1, '185/65 R15', 'Goodyear', 0, 'Duraplus', 4, '4.00', '4066.00'),
(110, 60, 1, '195/55 R16', 'Goodyear', 0, 'Assurance Triplemax', 4, '6003.00', '24012.00'),
(111, 61, 1, '185/60 R15', 'Goodyear', 0, 'DP-V1', 5, '3622.00', '18110.00'),
(112, 62, 1, '185/65 R15', 'Goodyear', 0, 'Assurance Triplemax', 4, '4091.00', '16364.00'),
(113, 63, 1, '175/65 R14', 'Goodyear', 0, 'Assurance Duraplus', 4, '3726.00', '14904.00'),
(114, 64, 1, '175/65 R14', 'Goodyear', 0, 'Assurance Triplemax', 4, '3853.00', '15412.00'),
(115, 65, 1, '145/80 R12', 'Goodyear', 0, 'Ducaro Himiler', 10, '2066.00', '20660.00'),
(116, 66, 1, '155/70 R13', 'Goodyear', 0, 'Duraplus', 8, '2861.00', '22888.00'),
(117, 67, 1, '155/80 R13', 'Goodyear', 0, 'Duraplus', 6, '3166.00', '18996.00'),
(118, 68, 1, '205/65 R15', 'Goodyear', 0, 'Assurance Duraplus', 8, '5272.00', '42176.00'),
(119, 69, 1, '175/70 R13', 'Goodyear', 0, 'Duraplus', 5, '3366.00', '16830.00'),
(120, 70, 1, '175/70 R14', 'Goodyear', 0, 'Duraplus', 5, '3833.00', '19165.00'),
(121, 71, 6, '3D Suzuki Swift/Dzire/Ritz Beige', 'Maxpider', 0, 'Full Set', 1, '3922.00', '3922.00'),
(122, 72, 1, '195/65 R15', 'Goodyear', 0, 'Assurance', 1, '4323.00', '4323.00'),
(123, 73, 1, '195/65 R15', 'Goodyear', 0, 'Assurance', 2, '4323.00', '8646.00'),
(124, 74, 3, '90/100-10', 'Ceat', 0, 'Zoom D T/L', 4, '818.00', '3272.00'),
(125, 75, 3, '90/90-12', 'Ceat', 0, 'Milaze T/L', 2, '777.00', '1554.00'),
(126, 75, 3, '100/90-17 ', 'Ceat', 0, 'Secura Zoom XL T/L', 2, '1378.00', '2756.00'),
(127, 76, 1, '100/00 R20', 'MRF', 0, 'S3C8', 6, '9610.00', '57660.00'),
(128, 76, 1, '100/00 R20', 'MRF', 0, 'Tube', 6, '1600.00', '9600.00'),
(129, 76, 1, '100/00 R20', 'MRF', 0, 'FLAP', 6, '600.00', '3600.00');

-- --------------------------------------------------------

--
-- Table structure for table `producttype`
--

CREATE TABLE IF NOT EXISTS `producttype` (
  `ProductTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductTypeName` varchar(50) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`ProductTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `producttype`
--

INSERT INTO `producttype` (`ProductTypeID`, `ProductTypeName`) VALUES
(1, '4 wheeler'),
(2, 'Tube'),
(3, '2 wheeler'),
(4, 'Mag wheel'),
(5, 'Battery'),
(6, 'Car care');

-- --------------------------------------------------------

--
-- Table structure for table `purchaseinvoice`
--

CREATE TABLE IF NOT EXISTS `purchaseinvoice` (
  `InvoiceID` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceDate` date NOT NULL,
  `SupplierID` int(11) NOT NULL,
  `Company` varchar(50) CHARACTER SET utf8 NOT NULL,
  `InvoiceNumber` varchar(50) CHARACTER SET utf8 NOT NULL,
  `TinNumber` varchar(50) CHARACTER SET utf8 NOT NULL,
  `SubTotal` decimal(19,2) NOT NULL DEFAULT '0.00',
  `VatAmount` decimal(19,2) NOT NULL DEFAULT '0.00',
  `TotalPaid` decimal(19,2) NOT NULL DEFAULT '0.00',
  `PaymentType` int(11) NOT NULL,
  `ChequeNo` varchar(20) CHARACTER SET utf8 NOT NULL,
  `ChequeDate` date NOT NULL,
  `Resolved` tinyint(1) NOT NULL,
  `Notes` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`InvoiceID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=77 ;

--
-- Dumping data for table `purchaseinvoice`
--

INSERT INTO `purchaseinvoice` (`InvoiceID`, `InvoiceDate`, `SupplierID`, `Company`, `InvoiceNumber`, `TinNumber`, `SubTotal`, `VatAmount`, `TotalPaid`, `PaymentType`, `ChequeNo`, `ChequeDate`, `Resolved`, `Notes`) VALUES
(1, '2017-04-01', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00006', '29270809280', '50760.00', '7360.20', '58120.20', 1, '', '0000-00-00', 0, 'Net amount is Rs.58120'),
(2, '2017-04-03', 0, 'SRI SAI MANJUNATHA ENTERPRISES', 'RS/38/17-18', '29861108249', '3701.00', '536.64', '4237.65', 1, '', '0000-00-00', 0, 'Rs.4238'),
(3, '2017-04-04', 0, 'GOODYEAR INDIA LIMITED', '336110017', '29550051750', '41196.65', '5973.51', '47170.16', 1, '', '0000-00-00', 0, 'Rs.47170'),
(4, '2017-06-04', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00073', '29270809280', '19832.00', '2875.64', '22707.64', 1, '', '0000-00-00', 0, 'RS.22708'),
(5, '2017-04-07', 0, 'GOODYEAR INDIA LIMITED', '336110034', '29550051750', '46932.56', '6805.22', '53737.78', 1, '', '0000-00-00', 0, 'RS.53739'),
(6, '2017-04-08', 0, 'KOLLEENAL', '20148/17-18', '29330751435', '4192.08', '607.85', '4799.93', 1, '', '0000-00-00', 0, 'Rs.4800'),
(7, '2017-04-08', 0, 'GENERAL TYRES', 'CM-11978', '29250065883', '14061.14', '2038.87', '16100.01', 1, '', '0000-00-00', 0, 'Rs.16100'),
(8, '2017-04-08', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00106', '29270809280', '48796.00', '7075.42', '55871.42', 1, '', '0000-00-00', 0, 'RS.55871'),
(9, '2017-04-11', 0, 'GOODYEAR INDIA LIMITED', '336110106', '29550051750', '77301.88', '11208.77', '88510.65', 1, '', '0000-00-00', 0, 'RS.88511'),
(10, '2017-04-11', 0, 'GOODYEAR INDIA LIMITED', '336110107', '29550051750', '26611.62', '3858.68', '30470.30', 1, '', '0000-00-00', 0, 'RS.30470'),
(11, '2017-04-12', 0, 'GENERAL TYRES', 'CM-11995', '29250065883', '9956.34', '1443.67', '11400.01', 1, '', '0000-00-00', 0, 'RS.11400'),
(12, '2017-04-14', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00232', '29270809280', '33512.00', '4859.24', '38371.24', 1, '', '0000-00-00', 0, 'RS.38371'),
(13, '2017-04-19', 0, 'SRI SKANDA TYRES', 'SSTCR/1718/288', '29160889705', '10515.18', '1524.70', '12039.88', 1, '', '0000-00-00', 0, 'RS.12039'),
(14, '2017-04-19', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00338', '29270809280', '13964.00', '2024.78', '15988.78', 1, '', '0000-00-00', 0, 'RS.15989'),
(15, '2017-04-20', 0, 'HOT TRACKS', 'HT/17-17/124', '29130145974', '5297.00', '768.07', '6065.07', 1, '', '0000-00-00', 0, 'RS.6065'),
(16, '2017-04-21', 0, 'SSS ENTERPRISES', '16052', '29950053082', '101310.00', '14689.95', '115999.95', 1, '', '0000-00-00', 0, 'RS.116000'),
(17, '2017-04-22', 0, 'SRI SAI MANJUNATHA ENTERPRISES', 'RS/661/17-18', '29861108249', '3074.00', '445.73', '3519.73', 1, '', '0000-00-00', 0, 'RS.3520'),
(18, '2017-04-22', 0, 'GENERAL TYRES', 'GM-27881', '29250065883', '6593.89', '956.11', '7550.00', 1, '', '0000-00-00', 0, 'RS.7550'),
(19, '2017-04-22', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00391', '29270809280', '50032.00', '7254.64', '57286.64', 1, '', '0000-00-00', 0, 'RS.57287'),
(20, '2017-04-24', 0, 'GURU TRADERS', '83', '29230214013', '2530.00', '366.85', '2896.85', 1, '', '0000-00-00', 0, 'RS.2897'),
(21, '2017-04-25', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00438', '29270809280', '32358.00', '4691.91', '37049.91', 1, '', '0000-00-00', 0, 'RS.37050'),
(22, '2017-04-26', 0, 'GENERAL TYRES', 'GM-12060', '29250065883', '16419.24', '2380.79', '18800.03', 1, '', '0000-00-00', 0, 'RS.18800'),
(23, '2017-04-27', 0, 'GENERAL TYRES', 'GM-12070', '29250065883', '21338.00', '3094.01', '24432.01', 1, '', '0000-00-00', 0, 'RS.24432'),
(24, '2017-04-28', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00610', '29270809280', '10800.00', '1566.00', '12366.00', 1, '', '0000-00-00', 0, 'RS.12366'),
(25, '2017-04-28', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00612', '29270809280', '54075.00', '7840.88', '61915.88', 1, '', '0000-00-00', 0, 'RS.61916'),
(26, '2017-04-28', 0, 'HOT TRACKS', 'HT/17-18/209', '29130145974', '5297.00', '768.07', '6065.07', 1, '', '0000-00-00', 0, 'RS.6065'),
(27, '2017-04-28', 0, 'GOODYEAR INDIA LIMITED', '336110593', '29550051750', '35823.42', '5194.40', '41017.82', 1, '', '0000-00-00', 0, 'RS.41019'),
(28, '2017-04-28', 0, 'GOODYEAR INDIA LIMITED', '336110592', '29550051750', '69434.60', '10068.02', '79502.62', 1, '', '0000-00-00', 0, 'RS.79504'),
(29, '2017-04-29', 0, 'SLNP PITSTOP', 'SAL1-SN-180', '29340070635', '7772.93', '1127.07', '8900.00', 1, '', '0000-00-00', 0, 'RS.8900'),
(30, '2017-04-29', 0, 'GENERAL TYRES', 'GM-12070', '29250065883', '22627.08', '3280.93', '25908.01', 1, '', '0000-00-00', 0, 'RS.25908'),
(31, '2017-04-28', 0, 'YOKOHAMA INDIA PRIVATE LIMITED', '17-18/BAN/TI-00613', '29270809280', '10756.00', '1559.62', '12315.62', 1, '', '0000-00-00', 0, 'RS.12316'),
(32, '2017-04-29', 0, 'GOODYEAR INDIA LIMITED', '336110644', '29550051750', '69984.46', '10147.75', '80132.21', 1, '', '0000-00-00', 0, 'RS.80132'),
(33, '2017-04-29', 0, 'GOODYEAR INDIA LIMITED', '336110645', '29550051750', '58775.82', '8522.49', '67298.31', 1, '', '0000-00-00', 0, 'RS.67298'),
(34, '2017-04-29', 0, 'GOODYEAR INDIA LIMITED', '336110646', '29550051750', '82590.21', '11975.58', '94565.79', 1, '', '0000-00-00', 0, 'RS.94566'),
(35, '2017-04-30', 0, 'GOODYEAR INDIA LIMITED', '336110749', '29550051750', '2612.02', '378.74', '2990.76', 1, '', '0000-00-00', 0, 'RS.2991'),
(36, '2017-05-02', 0, 'Good Year India Ltd', '336110751', '29550051750', '43632.00', '6326.64', '49958.64', 1, '', '0000-00-00', 0, 'After discount 46321'),
(37, '2017-05-04', 0, 'Shiva Enterprises', '346', '29930083357', '9540.00', '1383.30', '10923.30', 1, '', '0000-00-00', 0, ''),
(38, '2017-05-04', 0, 'Yokohama India Pvt Ltd', '17-18/BAN/TI-00742', '29270809280', '18480.00', '2679.60', '21159.60', 1, '', '0000-00-00', 0, ''),
(39, '2017-05-04', 0, 'Yokohama India Pvt Ltd', '17-18/BAN/TI-00742', '29270809280', '18480.00', '2679.60', '21159.60', 1, '', '0000-00-00', 0, ''),
(40, '2017-05-04', 0, 'PLATI India Pvt Ltd', '87', '29041217534', '18088.00', '2622.76', '20710.76', 1, '', '0000-00-00', 0, ''),
(41, '2017-05-08', 0, 'General Tyres', 'GM-28115', '29250065883', '13100.00', '1899.50', '14999.50', 1, '', '0000-00-00', 0, '15000 round off'),
(42, '2017-05-02', 0, 'SSS Enterprises', '16130', '29950053082', '12751.08', '1848.91', '14599.99', 1, '', '0000-00-00', 0, '14600 Round Off'),
(43, '2017-05-08', 0, 'Good Year India Ltd', '336110804', '29550051750', '90445.00', '13114.52', '103559.52', 1, '', '0000-00-00', 0, '93859 After discount'),
(44, '2017-05-15', 0, 'Guru Traders', '157', '29230214013', '2648.00', '383.96', '3031.96', 1, '', '0000-00-00', 0, '3032 Round Off'),
(45, '2017-05-11', 0, 'Yokohama India Pvt Ltd', '17-18/BAN/TI-00872', '29270809280', '16380.00', '2375.10', '18755.10', 1, '', '0000-00-00', 0, ''),
(46, '2017-05-17', 0, 'Yokohama India Pvt Ltd', '17-18/BAN/TI-00987', '29270809280', '19080.00', '2766.60', '21846.60', 1, '', '0000-00-00', 0, '21847 Round Off'),
(47, '2017-05-17', 0, 'Yokohama India Pvt Ltd', '17-18/BAN/TI-00990', '29270809280', '7892.00', '1144.34', '9036.34', 1, '', '0000-00-00', 0, ''),
(48, '2017-05-18', 0, 'General Tyres', 'GM-28241', '29250065883', '9554.60', '1385.42', '10940.02', 1, '', '0000-00-00', 0, ''),
(49, '2017-05-18', 0, 'General Tyres', 'CM-12155', '29250065883', '13117.92', '1902.10', '15020.02', 1, '', '0000-00-00', 0, ''),
(50, '2017-05-19', 0, 'General Tyres', 'GM-28256', '29250065883', '10253.28', '1486.73', '11740.01', 1, '', '0000-00-00', 0, ''),
(51, '2017-05-19', 0, 'General Tyres', 'CM-12168', '29250065883', '37449.80', '5430.22', '42880.02', 1, '', '0000-00-00', 0, ''),
(52, '2017-05-27', 0, 'Shiva Enterprises', '620', '29930083357', '4446.00', '644.67', '5090.67', 1, '', '0000-00-00', 0, ''),
(53, '2017-05-27', 0, 'General Tyres', 'GM-28370', '29950053082', '13467.24', '1952.75', '15419.99', 1, '', '0000-00-00', 0, '15420 Round Off'),
(54, '2017-05-30', 0, 'Kerala Steel Associates', '4597', '29780207454', '10288.00', '1491.76', '11779.76', 3, '000000', '2017-06-10', 0, ''),
(55, '2017-05-30', 0, 'Kerala Steel Associates', '4585', '29780207454', '18725.00', '2715.13', '21440.13', 3, '000000', '2017-06-15', 0, ''),
(56, '2017-05-30', 0, 'Kerala Steel Associates', '4585', '29780207454', '19225.00', '2787.63', '22012.63', 3, '000000', '2017-06-15', 0, ''),
(57, '2017-05-30', 0, 'Kerala Steel Associates', '4585', '29780207454', '27250.00', '3951.25', '31201.25', 3, '000000', '2017-06-05', 0, ''),
(58, '2017-06-05', 0, 'Kerala Steel Associates', '4587', '29780207454', '19050.00', '2762.25', '21812.25', 3, '000000', '2017-06-05', 0, ''),
(59, '2017-05-30', 0, 'Kerala Steel Associates', '4587', '29780207454', '16.00', '2.32', '18.32', 3, '000000', '2017-06-15', 0, ''),
(60, '2017-05-30', 0, 'Kerala Steel Associates', '4587', '29780207454', '24012.00', '3481.74', '27493.74', 3, '000000', '2017-06-05', 0, ''),
(61, '2017-05-30', 0, 'Kerala Steel Associates', '4584', '29780207454', '18110.00', '2625.95', '20735.95', 3, '000000', '2017-06-05', 0, ''),
(62, '2017-05-30', 0, 'Kerala Steel Associates', '4584', '29780207454', '16364.00', '2372.78', '18736.78', 3, '000000', '2017-06-05', 0, ''),
(63, '2017-06-05', 0, 'Kerala Steel Associates', '4584', '29780207454', '14904.00', '2161.08', '17065.08', 3, '000000', '2017-06-05', 0, ''),
(64, '2017-05-30', 0, 'Kerala Steel Associates', '4584', '', '15412.00', '2234.74', '17646.74', 3, '000000', '2017-06-15', 0, ''),
(65, '2017-06-05', 0, 'Kerala Steel Associates', '4583', '', '20660.00', '2995.70', '23655.70', 3, '000000', '2017-06-05', 0, ''),
(66, '2017-05-30', 0, 'Kerala Steel Associates', '4583', '', '22888.00', '3318.76', '26206.76', 3, '000000', '2017-06-05', 0, ''),
(67, '2017-05-30', 0, 'Kerala Steel Associates', '4583', '', '18996.00', '2754.42', '21750.42', 3, '000000', '2017-06-05', 0, ''),
(68, '2017-05-30', 0, 'Kerala Steel Associates', '4586', '', '42176.00', '6115.52', '48291.52', 3, '000000', '2017-06-05', 0, ''),
(69, '2017-05-30', 0, 'Kerala Steel Associates', '4586', '', '16830.00', '2440.35', '19270.35', 3, '000000', '2017-06-05', 0, ''),
(70, '2017-05-30', 0, 'Kerala Steel Associates', '4586', '', '19165.00', '2778.93', '21943.92', 3, '000000', '2017-06-05', 0, ''),
(71, '2017-05-30', 0, 'Hottracks', 'HT/17-18/456', '29130145974', '3922.00', '568.69', '4490.69', 3, '000000', '2017-06-05', 0, ''),
(72, '2017-05-31', 0, 'General Tyres', 'CM-12230', '29250065883', '4323.00', '626.84', '4949.84', 1, '', '0000-00-00', 0, ''),
(73, '2017-05-31', 0, 'General Tyres', 'GM 28419', '29250065883', '8646.00', '1253.67', '9899.67', 1, '', '0000-00-00', 0, ''),
(74, '2017-06-02', 0, 'Sri Skanda Tyres', 'SSTCR/17/18/1022', '29160889705', '3272.00', '474.44', '3746.44', 1, '', '0000-00-00', 0, ''),
(75, '2017-06-02', 0, 'Sri Skanda Tyres', 'SSTCR/1718/1022', '29160889705', '4310.00', '624.95', '4934.95', 1, '', '0000-00-00', 0, ''),
(76, '2017-06-05', 0, 'Avnash TYres', '1462', '29710074845', '70860.00', '0.00', '70860.00', 1, '', '0000-00-00', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `RoleID` int(11) NOT NULL AUTO_INCREMENT,
  `RoleName` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`RoleID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Roles for the user' AUTO_INCREMENT=4 ;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`RoleID`, `RoleName`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'Helper');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE IF NOT EXISTS `sales` (
  `InvoiceNumber` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceDateTime` datetime NOT NULL,
  `CustomerName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CustomerPhone` varchar(10) CHARACTER SET utf8 NOT NULL,
  `CustomerTIN` varchar(20) CHARACTER SET utf8 NOT NULL,
  `CustomerPAN` varchar(15) CHARACTER SET utf8 NOT NULL,
  `VehicleNumber` varchar(30) CHARACTER SET utf8 NOT NULL,
  `VehicleMileage` varchar(20) CHARACTER SET utf8 NOT NULL,
  `BasicAmount` decimal(19,2) NOT NULL DEFAULT '0.00',
  `Discount` decimal(19,2) NOT NULL DEFAULT '0.00',
  `Vat` decimal(19,2) NOT NULL DEFAULT '0.00',
  `AmountPaid` decimal(19,2) NOT NULL DEFAULT '0.00',
  `PaymentType` int(11) NOT NULL,
  `ChequeNo` varchar(20) CHARACTER SET utf8 NOT NULL,
  `chequeDate` date NOT NULL,
  `Resolved` tinyint(1) NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Notes` varchar(255) CHARACTER SET utf8 NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`InvoiceNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `salesproducts`
--

CREATE TABLE IF NOT EXISTS `salesproducts` (
  `SaleID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) NOT NULL,
  `BrandName` varchar(20) CHARACTER SET utf8 NOT NULL,
  `Productsize` varchar(60) CHARACTER SET utf8 NOT NULL,
  `Pattern` varchar(20) CHARACTER SET utf8 NOT NULL,
  `ProductType` varchar(20) CHARACTER SET utf8 NOT NULL,
  `Qty` int(11) NOT NULL,
  `CostPrice` decimal(19,2) NOT NULL DEFAULT '0.00',
  `SalePrice` decimal(19,2) NOT NULL DEFAULT '0.00',
  `InvoiceNumber` int(11) NOT NULL,
  PRIMARY KEY (`SaleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `service`
--

CREATE TABLE IF NOT EXISTS `service` (
  `InvoiceNumber` int(11) NOT NULL AUTO_INCREMENT,
  `InvoiceDateTime` datetime NOT NULL,
  `CustomerName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `CustomerPhone` varchar(10) CHARACTER SET utf8 NOT NULL,
  `VehicleNumber` varchar(30) CHARACTER SET utf8 NOT NULL,
  `VehicleMileage` varchar(20) CHARACTER SET utf8 NOT NULL,
  `SubTotal` decimal(19,2) NOT NULL DEFAULT '0.00',
  `Discount` decimal(19,2) NOT NULL DEFAULT '0.00',
  `AmountPaid` decimal(19,2) NOT NULL DEFAULT '0.00',
  `Address` varchar(255) CHARACTER SET utf8 COLLATE utf8_german2_ci NOT NULL,
  `Note` varchar(255) CHARACTER SET utf8 NOT NULL,
  `UserID` int(11) NOT NULL,
  `LastUpdated` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`InvoiceNumber`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5662 ;

--
-- Dumping data for table `service`
--

INSERT INTO `service` (`InvoiceNumber`, `InvoiceDateTime`, `CustomerName`, `CustomerPhone`, `VehicleNumber`, `VehicleMileage`, `SubTotal`, `Discount`, `AmountPaid`, `Address`, `Note`, `UserID`, `LastUpdated`) VALUES
(5601, '2017-05-16 02:45:00', 'XYZ', '1234567890', 'KA-44 7832', '00000', '300.00', '0.00', '250.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5602, '2017-05-16 00:00:00', 'XYZ', '1234567890', 'KA-18-P-9414', '000', '700.00', '0.00', '700.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5603, '2017-05-17 01:15:00', 'XYZ', '1234567890', 'TN-63-R-656', '000', '300.00', '0.00', '300.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5604, '2017-05-18 01:45:00', 'XYZ', '1234567890', 'KA-03-MV-7934', '000', '250.00', '0.00', '250.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5605, '2017-05-18 02:15:00', 'XYZ', '1234567890', 'KA-51-MD-4008', '000', '250.00', '0.00', '250.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5606, '2017-05-18 02:45:00', 'XYZ', '1234567890', 'KA-04-AA-3483', '000', '500.00', '0.00', '500.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5607, '2017-05-18 03:00:00', 'XYZ', '1234567890', 'KA-04-MK-271', '000', '300.00', '0.00', '300.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5608, '2017-05-19 00:00:00', 'XYZ', '1234567890', 'KA-02-ME-9218', '000', '650.00', '0.00', '650.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5609, '2017-05-19 00:00:00', 'XYZ', '1111111111', 'KA-04-MK-7753', '000', '250.00', '0.00', '250.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5610, '2017-05-19 00:00:00', 'XYZ', '1111111111', 'KA-03-MV-4566', '000', '700.00', '0.00', '700.00', 'N/A', '', 1, '0000-00-00 00:00:00'),
(5611, '2017-05-19 04:00:00', 'XYZ', '1234567897', 'KA 04 MN 1029', '000000', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5612, '2017-05-19 04:00:00', 'XXX', '1010101010', 'KA 04 MH 1645', '0000', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5613, '2017-05-19 04:00:00', 'SUNIL', '9898989898', 'KA 04 MJ 139', '000', '650.00', '0.00', '650.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5614, '2017-05-19 04:00:00', 'GKG', '8787878787', 'KA 15 7574', '0000', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5615, '2017-05-20 04:00:00', 'CDF', '9876544532', 'KA 03 ML 4302', '00', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5616, '2017-05-20 04:00:00', 'SDF', '9874568765', 'KA 05MK 4415', '00', '700.00', '0.00', '700.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5617, '2017-05-20 04:00:00', 'RAMESH', '7234567897', 'KA 04 MR 2237', '00', '450.00', '0.00', '450.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5618, '2017-05-20 04:00:00', 'RAVI', '8765438976', 'KA 04 MF 8619', '00', '650.00', '0.00', '650.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5619, '2017-05-20 04:00:00', 'KUMAR', '9845238645', 'KA 01 MP 2503', '00', '350.00', '0.00', '350.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5620, '2017-05-20 04:00:00', 'CDF', '8676543267', 'KA 04 AA 4390', '00', '650.00', '0.00', '650.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5621, '2017-05-20 04:00:00', 'RAJA', '9876545674', 'KA 04 ML 2825', '00', '290.00', '0.00', '290.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5622, '2017-05-20 04:00:00', 'AZEEZ', '8765438952', 'KA 04 MN 0636', '00', '650.00', '0.00', '650.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5623, '2017-05-20 04:00:00', 'SAMATH', '9845367854', 'KA 04 MJ 1530', '00', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5624, '2017-05-20 04:00:00', 'RAM', '9856478966', 'KA 04 ME 3055', '00', '750.00', '0.00', '750.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5625, '2017-05-30 14:12:00', 'ARUN', '8762515456', 'KA 04 MD 3764', '0000', '650.00', '0.00', '650.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5626, '2017-05-20 04:00:00', 'ABCD', '9988776600', 'KA 04 MH 8099', '1234', '550.00', '0.00', '550.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5627, '2017-05-20 04:00:00', 'ARUN', '8762515453', 'KA 02 MO 1860', '22344', '260.00', '0.00', '260.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5628, '2017-05-20 14:12:00', 'SUNIL', '9611493366', 'KA 19 MG 919', '33221', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5629, '0000-00-00 00:00:00', 'ABC', '1122334455', 'KA 36 B 2676', '1122', '450.00', '0.00', '450.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5630, '2017-05-20 14:12:00', 'SUNI', '9988776655', 'KA 03 MW 549', '0000', '350.00', '0.00', '350.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5631, '2017-05-22 14:12:00', 'KUMAR', '9977665544', 'KA 51 MC 6665', '0000', '800.00', '0.00', '800.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5632, '2017-05-22 14:12:00', 'ASHOK', '8877663322', 'KA 04 MF 9673', '00000', '700.00', '0.00', '700.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5633, '2017-05-22 14:12:00', 'ABCD', '9977665544', 'KA 04 MN 2335', '000', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5634, '2017-05-22 14:12:00', 'ABCD', '7788995566', 'KA 03 MV 2254', '0000', '575.00', '0.00', '575.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5635, '2017-05-23 14:12:00', 'NITHIN', '9900776633', 'KA  04 MF 0423', 'NA', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5636, '2017-05-23 14:12:00', 'ABCD', '9988776655', 'KA 03 AB 1307', '0000', '870.00', '0.00', '870.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5637, '2017-05-23 14:12:00', 'ABCD', '9900889977', 'KA 04 MD 2099', '00000', '800.00', '0.00', '800.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5638, '2017-05-24 14:12:00', 'SUNIL', '9988776600', 'KA 02 MH 237', '00000', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5639, '2017-05-24 14:12:00', 'ABCD', '9988770066', 'KA 50 N 6343', '0000', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5640, '2017-05-24 14:12:00', 'ASHK', '9900887766', 'KA 02 N 9775', '0000', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5641, '2017-05-25 14:12:00', 'ABCD', '9900887766', '12 B 112684 X', '00000', '650.00', '0.00', '650.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5642, '2017-05-25 14:12:00', 'SUNI', '9911223344', 'KA 04 MR 0891', '00000', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5643, '2017-05-25 14:12:00', 'ABCD', '9988776655', 'KA 11 MC 911', '000', '500.00', '0.00', '500.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5644, '2017-05-25 14:12:00', 'ABCD', '9988776600', 'KA 02 MB 8485', '0000', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5645, '2017-05-25 14:12:00', 'SUNIL', '9988007799', 'KA 25 AA 6421', '0000', '450.00', '0.00', '450.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5646, '2017-05-25 14:12:00', 'ABCD', '9988773322', 'PY 01 BT 4141', '0000', '550.00', '0.00', '550.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5647, '2017-05-26 14:12:00', 'ABCD', '9900775544', 'WB 06F 1377', '000000', '500.00', '0.00', '500.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5648, '2017-05-26 14:12:00', 'ABCD', '9911223344', 'KA 04 MP 6022', '0000', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5649, '2017-05-26 14:12:00', 'ABCD', '9922005511', 'KA 04 ME 1917', '00000', '350.00', '0.00', '350.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5650, '2017-05-26 14:12:00', 'ABCD', '9988772211', 'KA 51 MD 0893', '00000', '250.00', '0.00', '250.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5651, '2017-05-26 14:12:00', 'ABCD', '9988776611', 'KA 04 MC 9501', '0000', '200.00', '0.00', '200.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5652, '2017-05-27 14:12:00', 'ABCD', '9922330066', 'DL 8C 9793', '00000', '650.00', '0.00', '650.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5653, '2017-05-27 14:12:00', 'ABCD', '9900112244', 'KA 02 MD 6948', '00000', '350.00', '0.00', '350.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5654, '2017-05-27 14:12:00', 'ABCD', '9900887766', 'KA', '0000', '350.00', '0.00', '350.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5655, '2017-05-27 14:12:00', 'ABCD', '9988998800', 'UP 32 DT 0663', '0000', '325.00', '0.00', '325.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5656, '2017-05-27 14:12:00', 'ABCD', '9911223344', 'GJ O6 HL 2255', '00000', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5657, '2017-05-29 14:12:00', 'ABCD', '9900887755', 'KA 41 P 422', '0000', '700.00', '0.00', '700.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5658, '2017-05-30 14:12:00', 'ABCD', '9900554433', 'KA 02 MB 3649', '00000', '600.00', '0.00', '600.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5659, '2017-05-30 14:12:00', 'ANANTH', '9977664422', 'KA 22 N 9272', '0000', '300.00', '0.00', '300.00', 'NA', '', 1, '0000-00-00 00:00:00'),
(5660, '2017-05-30 14:12:00', 'BWC', '9988776655', 'KA 01 MM 4001', '0000', '2500.00', '0.00', '2500.00', 'NA', 'Service not done, only Bill~Peter', 1, '0000-00-00 00:00:00'),
(5661, '2017-05-30 09:53:00', 'ABC', '2124252623', 'KA 03 MV 696', '0000', '450.00', '0.00', '450.00', 'NA', '', 1, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `serviceable`
--

CREATE TABLE IF NOT EXISTS `serviceable` (
  `ItemID` int(11) NOT NULL AUTO_INCREMENT,
  `Item` varchar(50) NOT NULL,
  `Price` decimal(19,2) NOT NULL,
  `Depricated` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `serviceable`
--

INSERT INTO `serviceable` (`ItemID`, `Item`, `Price`, `Depricated`) VALUES
(1, 'Wheel Balancing', '100.00', b'0'),
(2, 'Wheel Alignment', '300.00', b'0'),
(3, 'New Serviceable34', '230.00', b'1'),
(4, 'New Serviceable', '500.00', b'1'),
(5, 'New Serviceable', '0.00', b'0'),
(6, 'Tyre Changing', '50.00', b'0'),
(7, 'Nitrogen Top Up', '40.00', b'0'),
(8, 'Nitrogen Full', '100.00', b'0'),
(9, 'Puncture', '100.00', b'0'),
(10, 'Weights', '1.00', b'0');

-- --------------------------------------------------------

--
-- Table structure for table `serviceitems`
--

CREATE TABLE IF NOT EXISTS `serviceitems` (
  `ServiceItemID` int(11) NOT NULL AUTO_INCREMENT,
  `ServiceableID` int(11) NOT NULL,
  `ServiceableDispName` varchar(50) CHARACTER SET utf8 NOT NULL,
  `Qty` int(11) NOT NULL,
  `Price` decimal(19,2) NOT NULL DEFAULT '0.00',
  `InvoiceNumber` int(11) NOT NULL,
  PRIMARY KEY (`ServiceItemID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=118 ;

--
-- Dumping data for table `serviceitems`
--

INSERT INTO `serviceitems` (`ServiceItemID`, `ServiceableID`, `ServiceableDispName`, `Qty`, `Price`, `InvoiceNumber`) VALUES
(1, 2, 'Wheel Alignment', 1, '300.00', 5601),
(2, 2, 'Wheel Alignment', 1, '300.00', 5602),
(3, 1, 'Wheel Balancing', 4, '75.00', 5602),
(4, 10, 'Weights', 100, '1.00', 5602),
(5, 2, 'Wheel Alignment', 1, '300.00', 5603),
(6, 2, 'Wheel Alignment', 1, '250.00', 5604),
(7, 2, 'Wheel Alignment', 1, '250.00', 5605),
(8, 2, 'Wheel Alignment', 1, '300.00', 5606),
(9, 1, 'Wheel Balancing', 2, '75.00', 5606),
(10, 10, 'Weights', 50, '1.00', 5606),
(11, 2, 'Wheel Alignment', 1, '300.00', 5607),
(12, 1, 'Wheel Balancing', 4, '75.00', 5608),
(13, 2, 'Wheel Alignment', 1, '250.00', 5608),
(14, 10, 'Weights', 100, '1.00', 5608),
(15, 2, 'Wheel Alignment', 1, '250.00', 5609),
(16, 2, 'Wheel Alignment', 1, '300.00', 5610),
(17, 1, 'Wheel Balancing', 4, '75.00', 5610),
(18, 10, 'Weights', 100, '1.00', 5610),
(19, 2, 'Wheel Alignment', 1, '300.00', 5611),
(20, 2, 'Wheel Alignment', 1, '300.00', 5612),
(21, 2, 'Wheel Alignment', 1, '350.00', 5613),
(22, 9, 'Puncture', 3, '100.00', 5613),
(23, 2, 'Wheel Alignment', 1, '250.00', 5614),
(24, 2, 'Wheel Alignment', 1, '250.00', 5615),
(25, 2, 'Wheel Alignment', 1, '350.00', 5616),
(26, 1, 'Wheel Balancing', 4, '75.00', 5616),
(27, 10, 'Weights', 50, '1.00', 5616),
(28, 2, 'Wheel Alignment', 1, '350.00', 5617),
(29, 8, 'Nitrogen Full', 1, '100.00', 5617),
(30, 2, 'Wheel Alignment', 1, '300.00', 5618),
(31, 1, 'Wheel Balancing', 4, '75.00', 5618),
(32, 10, 'Weights', 50, '1.00', 5618),
(33, 2, 'Wheel Alignment', 1, '350.00', 5619),
(34, 1, 'Wheel Balancing', 4, '75.00', 5620),
(35, 2, 'Wheel Alignment', 1, '300.00', 5620),
(36, 10, 'Weights', 50, '1.00', 5620),
(37, 2, 'Wheel Alignment', 1, '250.00', 5621),
(38, 8, 'Nitrogen Full', 1, '40.00', 5621),
(39, 1, 'Wheel Balancing', 4, '75.00', 5622),
(40, 2, 'Wheel Alignment', 1, '300.00', 5622),
(41, 10, 'Weights', 50, '1.00', 5622),
(42, 2, 'Wheel Alignment', 1, '250.00', 5623),
(43, 1, 'Wheel Balancing', 4, '75.00', 5624),
(44, 2, 'Wheel Alignment', 1, '300.00', 5624),
(45, 9, 'Puncture', 1, '100.00', 5624),
(46, 10, 'Weights', 50, '1.00', 5624),
(47, 2, 'Wheel Alignment', 1, '250.00', 5625),
(48, 1, 'Wheel Balancing', 4, '75.00', 5625),
(49, 10, 'Weights', 1, '100.00', 5625),
(50, 2, 'Wheel Alignment', 1, '300.00', 5626),
(51, 1, 'Wheel Balancing', 2, '75.00', 5626),
(52, 10, 'Weights', 100, '1.00', 5626),
(53, 2, 'Wheel Alignment', 1, '200.00', 5627),
(54, 5, 'New Serviceable', 1, '60.00', 5627),
(55, 2, 'Wheel Alignment', 1, '300.00', 5628),
(56, 2, 'Wheel Alignment', 1, '350.00', 5629),
(57, 9, 'Puncture', 1, '100.00', 5629),
(58, 2, 'Wheel Alignment', 1, '350.00', 5630),
(59, 2, 'Wheel Alignment', 1, '350.00', 5631),
(60, 1, 'Wheel Balancing', 4, '75.00', 5631),
(61, 10, 'Weights', 150, '1.00', 5631),
(62, 2, 'Wheel Alignment', 1, '300.00', 5632),
(63, 1, 'Wheel Balancing', 4, '75.00', 5632),
(64, 10, 'Weights', 100, '1.00', 5632),
(65, 2, 'Wheel Alignment', 1, '250.00', 5633),
(66, 2, 'Wheel Alignment', 1, '250.00', 5634),
(67, 1, 'Wheel Balancing', 3, '75.00', 5634),
(68, 8, 'Nitrogen Full', 1, '100.00', 5634),
(69, 2, 'Wheel Alignment', 1, '300.00', 5635),
(70, 2, 'Wheel Alignment', 1, '350.00', 5636),
(71, 1, 'Wheel Balancing', 4, '75.00', 5636),
(72, 6, 'Tyre Changing', 3, '40.00', 5636),
(73, 10, 'Weights', 100, '1.00', 5636),
(74, 2, 'Wheel Alignment', 1, '350.00', 5637),
(75, 1, 'Wheel Balancing', 4, '75.00', 5637),
(76, 9, 'Puncture', 1, '100.00', 5637),
(77, 10, 'Weights', 50, '1.00', 5637),
(78, 2, 'Wheel Alignment', 1, '250.00', 5638),
(79, 2, 'Wheel Alignment', 1, '300.00', 5639),
(80, 2, 'Wheel Alignment', 1, '250.00', 5640),
(81, 2, 'Wheel Alignment', 1, '250.00', 5641),
(82, 1, 'Wheel Balancing', 4, '75.00', 5641),
(83, 10, 'Weights', 100, '1.00', 5641),
(84, 2, 'Wheel Alignment', 1, '300.00', 5642),
(85, 2, 'Wheel Alignment', 1, '350.00', 5643),
(86, 1, 'Wheel Balancing', 2, '75.00', 5643),
(87, 2, 'Wheel Alignment', 1, '250.00', 5644),
(88, 2, 'Wheel Alignment', 1, '300.00', 5645),
(89, 1, 'Wheel Balancing', 2, '75.00', 5645),
(90, 2, 'Wheel Alignment', 1, '250.00', 5646),
(91, 1, 'Wheel Balancing', 2, '75.00', 5646),
(92, 6, 'Tyre Changing', 2, '50.00', 5646),
(93, 10, 'Weights', 50, '1.00', 5646),
(94, 2, 'Wheel Alignment', 1, '500.00', 5647),
(95, 2, 'Wheel Alignment', 1, '300.00', 5648),
(96, 2, 'Wheel Alignment', 1, '350.00', 5649),
(97, 2, 'Wheel Alignment', 1, '250.00', 5650),
(98, 2, 'Wheel Alignment', 1, '200.00', 5651),
(99, 2, 'Wheel Alignment', 1, '250.00', 5652),
(100, 1, 'Wheel Balancing', 4, '75.00', 5652),
(101, 10, 'Weights', 100, '1.00', 5652),
(102, 2, 'Wheel Alignment', 1, '350.00', 5653),
(103, 2, 'Wheel Alignment', 1, '350.00', 5654),
(104, 2, 'Wheel Alignment', 1, '250.00', 5655),
(105, 1, 'Wheel Balancing', 1, '75.00', 5655),
(106, 2, 'Wheel Alignment', 1, '300.00', 5656),
(107, 2, 'Wheel Alignment', 1, '300.00', 5657),
(108, 1, 'Wheel Balancing', 4, '75.00', 5657),
(109, 10, 'Weights', 100, '1.00', 5657),
(110, 2, 'Wheel Alignment', 1, '400.00', 5658),
(111, 9, 'Puncture', 2, '100.00', 5658),
(112, 2, 'Wheel Alignment', 1, '300.00', 5659),
(113, 2, 'Wheel Alignment', 1, '1400.00', 5660),
(114, 1, 'Wheel Balancing', 4, '250.00', 5660),
(115, 8, 'Nitrogen Full', 1, '100.00', 5660),
(116, 2, 'Wheel Alignment', 1, '350.00', 5661),
(117, 9, 'Puncture', 1, '100.00', 5661);

-- --------------------------------------------------------

--
-- Table structure for table `stockentries`
--

CREATE TABLE IF NOT EXISTS `stockentries` (
  `EntryID` int(11) NOT NULL AUTO_INCREMENT,
  `ProductID` int(11) NOT NULL,
  `Qty` decimal(19,0) DEFAULT NULL,
  `TansactionTypeID` int(11) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`EntryID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE IF NOT EXISTS `supplier` (
  `SupplierID` int(11) NOT NULL AUTO_INCREMENT,
  `SupplierName` varchar(50) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `Mobile` varchar(13) NOT NULL,
  `Email` varchar(50) NOT NULL,
  `Address` varchar(255) NOT NULL,
  `TinNumber` varchar(50) NOT NULL,
  `ContactPerson` varchar(50) NOT NULL,
  PRIMARY KEY (`SupplierID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tranasactiontype`
--

CREATE TABLE IF NOT EXISTS `tranasactiontype` (
  `TansactionTypeID` int(11) NOT NULL AUTO_INCREMENT,
  `TranasactionTypeName` varchar(50) NOT NULL,
  PRIMARY KEY (`TansactionTypeID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tranasactiontype`
--

INSERT INTO `tranasactiontype` (`TansactionTypeID`, `TranasactionTypeName`) VALUES
(1, 'ADD'),
(2, 'REMOVE'),
(3, 'PURCHASE'),
(4, 'SELL');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `UserID` int(11) NOT NULL AUTO_INCREMENT,
  `Email` varchar(45) CHARACTER SET utf8 NOT NULL,
  `Name` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Password` varchar(200) CHARACTER SET utf8 NOT NULL,
  `RoleID` int(11) NOT NULL,
  `TimeStamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `PasswordRecovery` varchar(60) CHARACTER SET utf8 DEFAULT NULL COMMENT 'This field will contain password recovery key. In case if user clicks on forgot password.',
  `UserPhone` varchar(10) CHARACTER SET utf8 NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Status` int(2) NOT NULL,
  `Hidden` int(2) NOT NULL,
  PRIMARY KEY (`UserID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`UserID`, `Email`, `Name`, `Password`, `RoleID`, `TimeStamp`, `PasswordRecovery`, `UserPhone`, `Address`, `Status`, `Hidden`) VALUES
(1, 'admin@admin.com', 'Jagdish', '$2y$10$8AK5FYhg.H0u/SySxjjNReu7RU21f2zNgz6rZPyqSSM0Y4e83BNy6', 2, '2017-05-30 21:36:56', NULL, '9567809867', '15/21 street1', 1, 0),
(2, '', 'Jaydeep', '$2y$10$8AK5FYhg.H0u/SySxjjNReu7RU21f2zNgz6rZPyqSSM0Y4e83BNy6', 1, '2017-05-30 21:36:57', NULL, '9033933946', '', 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
