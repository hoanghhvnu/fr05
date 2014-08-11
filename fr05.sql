-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 08, 2014 at 08:55 AM
-- Server version: 5.5.8
-- PHP Version: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fr05`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_sinhvien`
--

CREATE TABLE IF NOT EXISTS `tbl_sinhvien` (
  `sv_id` int(10) NOT NULL AUTO_INCREMENT,
  `sv_name` varchar(255) NOT NULL,
  `sv_email` varchar(255) NOT NULL,
  `sv_info` varchar(255) NOT NULL,
  `sv_address` varchar(255) NOT NULL,
  `sv_phone` int(11) NOT NULL,
  `sv_school` varchar(255) NOT NULL,
  `sv_avata` varchar(255) NOT NULL,
  `sv_gender` tinyint(1) NOT NULL,
  PRIMARY KEY (`sv_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `tbl_sinhvien`
--

INSERT INTO `tbl_sinhvien` (`sv_id`, `sv_name`, `sv_email`, `sv_info`, `sv_address`, `sv_phone`, `sv_school`, `sv_avata`, `sv_gender`) VALUES
(3, 'VietDQ', 'hoanghh@smartosc.com', 'snoeatusa', 'oeidoe', 988999888, 'DH cong nghe', 'aou.jpg', 2),
(4, 'HuanDT', 'tuan@gamil.com', 'nhoÃ¡uhÃ¡o', 'oeidoe', 988987897, 'DH cong nghe', 'aou.jpg', 1),
(7, 'oaeusaeu', 'Hoanghh@gamil.com', 'oausao', 'oaust', 988999999, 'oauaous', 'oausnao', 1),
(8, 'VietAN', 'oausnaosu@gami.lcom', 'oausahos', 'snhoaesnua', 976630383, 'DH Bach Khoa', 'oauo.jpg', 1),
(9, 'MinhND', 'oeuaosuao@gami.lcom', 'oustaohsn', 'snthoeasuoa', 976630383, 'aouaos', 'oausao', 2),
(10, 'VuNA', 'osuao@gmal.com', 'oeuasout', 'soantuhsao', 976630383, 'oasuhaso', 'ousao', 1),
(11, 'BinhND', 'oeauasou@gmail.com', 'aosuao', 'soauaos', 976630383, 'oatnu', 'oaeu', 1);
