-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 06:31 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `property_rental`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL,
  `phone` varchar(500) NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `address` text NOT NULL,
  `date_registered` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `phone`, `username`, `password`, `address`, `date_registered`) VALUES
(1, 'Admin', 'aadhamlebbefahriya01@gmail.com', '0754124001', 'admin123', '$2y$10$z5O2paPz305CA7LVmCzFoeqsZxlHRtXCeAZY8ysu1.vDbGNtlO7QK', 'address', '2025-09-15 19:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `agreements`
--

CREATE TABLE `agreements` (
  `id` int(11) NOT NULL,
  `property_id` int(10) UNSIGNED NOT NULL,
  `tenant_id` int(10) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `message` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Cancelled') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `agreements`
--

INSERT INTO `agreements` (`id`, `property_id`, `tenant_id`, `start_date`, `end_date`, `message`, `status`, `created_at`) VALUES
(1, 1, 4, '2025-10-15', '2026-11-15', 'for 1 year ', 'Approved', '2025-09-15 04:11:14');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `agreement_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `date_of_incident` date NOT NULL,
  `status` enum('Resolved','Pending') NOT NULL DEFAULT 'Pending',
  `date_time_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `landlords`
--

CREATE TABLE `landlords` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL,
  `phone` varchar(500) NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `address` text NOT NULL,
  `date_registereed` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `landlords`
--

INSERT INTO `landlords` (`id`, `name`, `email`, `phone`, `username`, `password`, `address`, `date_registereed`) VALUES
(1, 'sample landloard', 'aadhamlebbefahriya01@gmail.com', '+94222222222', 'landlord', '$2y$10$SVPl2IqKbuCpretR2SAZIeNJNronaJs1464JRfgjISXwr5rcOi7By', '75/2, main street, mavatta -09', '2025-05-06 18:31:08'),
(5, 'Sarah Liyanage', 'aadhamlebbefahriya01@gmail.com', '+94 70 345 6789', 'sarahsarah', '$2y$10$rikLWZwVLUmFoB.mrwMQhucPhxMk4ePJJTySQqCRJ5W.bI2exR.Ju', '321 Mount Lavinia, Colombo, Sri Lanka', '2025-05-07 21:22:08'),
(6, 'Michael Lee', 'aadhamlebbefahriya01@gmail.com', '+94 76 234 5678', 'Lee123', '$2y$10$JW71fI.dJM6J9IVIfEO8zOjcprVl.s06IqYu0YPol/zcTqR9W2Y4C', '789 Negombo Road, Negombo, Sri Lanka', '2025-05-07 21:26:47');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `agreement_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `card_name` varchar(1000) NOT NULL,
  `card_number` int(11) NOT NULL,
  `expiry_month` varchar(1000) NOT NULL,
  `expiry_year` varchar(1000) NOT NULL,
  `cvv` int(11) NOT NULL,
  `billing_address` text NOT NULL,
  `amount` varchar(1000) NOT NULL,
  `postal_code` varchar(1000) NOT NULL,
  `pay_month` varchar(1000) NOT NULL,
  `pay_year` varchar(1000) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `properties`
--

CREATE TABLE `properties` (
  `id` int(11) NOT NULL,
  `propertyTitle` varchar(500) NOT NULL,
  `propertyType` varchar(500) NOT NULL,
  `landlord_id` int(11) NOT NULL,
  `bedrooms` int(11) NOT NULL,
  `bathrooms` int(11) NOT NULL,
  `location` text NOT NULL,
  `rent` varchar(500) NOT NULL,
  `image` varchar(500) NOT NULL,
  `description` text NOT NULL,
  `date_registered` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `properties`
--

INSERT INTO `properties` (`id`, `propertyTitle`, `propertyType`, `landlord_id`, `bedrooms`, `bathrooms`, `location`, `rent`, `image`, `description`, `date_registered`) VALUES
(1, 'Cozy 2BHK Apartment', 'Apartment', 1, 3, 2, '123 Greenview St, Springfield', '5000', 'th.jpeg', 'vbvchg', '2025-09-12 10:20:50'),
(2, '3BHK House with Sea View in Colombo', 'House', 6, 3, 2, 'Colombo 7, Marine Drive, Colombo, Western Province', '42000', 'th (2).jpeg', 'This beautiful 3-bedroom house located in the heart of Colombo offers a stunning sea view from the living room and master bedroom. The property is fully furnished and includes modern amenities such as air conditioning, a spacious kitchen, and a private garden. Ideal for families looking for a serene and comfortable living space close to schools, hospitals, and shopping centers.', '2025-09-12 10:22:34'),
(4, '10 Perch Land in Kandy City', 'Villa', 5, 0, 0, 'Kandy, Sri Dalada Veediya Road', '50000', 'Kandy-Sri-Lanka.webp', 'A 10-perch residential land located in the heart of Kandy City. Suitable for residential or small commercial use. Close to schools, hospitals, and supermarkets.', '2025-09-15 20:30:21'),
(5, 'Modern Office Space in Colombo 07', 'Studio', 1, 0, 2, 'Colombo 07, Cinnamon Gardens', '70000', 'OIP (7).jpeg', 'A fully tiled 1200 sq. ft office space with AC, 2 bathrooms, parking for 5 vehicles, and 24/7 security. Ideal for IT firms, startups, or small corporates.', '2025-09-15 20:55:43'),
(6, '3-Story Commercial Building in Galle Town', 'Studio', 6, 0, 6, 'Galle, Main Street', '85000', 'OIP (8).jpeg', 'A three-story commercial building with over 6000 sq. ft floor area. Suitable for retail shops, banks, or showrooms. Located in the busiest street in Galle.', '2025-09-15 21:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tenates`
--

CREATE TABLE `tenates` (
  `id` int(11) NOT NULL,
  `name` varchar(500) NOT NULL,
  `email` varchar(500) NOT NULL,
  `phone` varchar(500) NOT NULL,
  `username` varchar(500) NOT NULL,
  `password` varchar(500) NOT NULL,
  `address` text NOT NULL,
  `dare_registered` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenates`
--

INSERT INTO `tenates` (`id`, `name`, `email`, `phone`, `username`, `password`, `address`, `dare_registered`) VALUES
(4, 'Saleem Sawla', 'aadhamlebbefahriya01@gmail.com', '0758210267', 'sawlaS', '$2y$10$gjWd2JCxLU9Hxo3uuPAMMu0.TaId2xnIeYWBTstb6skm0kT90vaja', 'Mavanalla-09', '2025-08-15 22:19:45'),
(5, 'Fahriya', 'aadhamlebbefahriya01@gmail.com', '0775821402', 'fahriya!076', '$2y$10$gUd/By255gkJqgFMk7Cf/.nUcJKjfpwEeh3mnYCZbegJwFibaEWOa', 'address', '2025-09-15 20:05:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `agreements`
--
ALTER TABLE `agreements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `landlords`
--
ALTER TABLE `landlords`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `properties`
--
ALTER TABLE `properties`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenates`
--
ALTER TABLE `tenates`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `agreements`
--
ALTER TABLE `agreements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `landlords`
--
ALTER TABLE `landlords`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `properties`
--
ALTER TABLE `properties`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tenates`
--
ALTER TABLE `tenates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
