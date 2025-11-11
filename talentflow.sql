-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 11, 2025 at 05:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `talentflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `artifacts`
--

CREATE TABLE `artifacts` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `kind` varchar(50) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `artifacts`
--

INSERT INTO `artifacts` (`id`, `task_id`, `kind`, `value`, `created_at`) VALUES
(6, 3, 'workerId', 'w_4232', '2025-11-09 17:57:24'),
(7, 3, 'ticketId', 'INC4260', '2025-11-09 17:57:24'),
(8, 3, 'userId', 'u_2480', '2025-11-09 17:57:24'),
(9, 3, 'eventId', 'evt_7813', '2025-11-09 17:57:24'),
(10, 3, 'docLink', 'https://docs.local/welcome/5172', '2025-11-09 17:57:24'),
(11, 7, 'eventId', 'evt_4024', '2025-11-09 18:05:29'),
(12, 8, 'offerDoc', 'https://docs.local/welcome/4987', '2025-11-09 18:07:51'),
(13, 9, 'workerId', 'w_9124', '2025-11-09 18:38:32'),
(14, 9, 'ticketId', 'INC8415', '2025-11-09 18:38:32'),
(15, 9, 'userId', 'u_6944', '2025-11-09 18:38:32'),
(16, 9, 'eventId', 'evt_9922', '2025-11-09 18:38:32'),
(17, 9, 'docLink', 'https://docs.local/welcome/6126', '2025-11-09 18:38:32'),
(18, 10, 'eventId', 'evt_1677', '2025-11-09 18:39:56'),
(19, 11, 'offerDoc', 'https://docs.local/welcome/2117', '2025-11-09 18:41:18'),
(20, 12, 'workerId', 'w_3339', '2025-11-11 13:21:55'),
(21, 12, 'ticketId', 'INC8921', '2025-11-11 13:21:55'),
(22, 12, 'userId', 'u_3330', '2025-11-11 13:21:55'),
(23, 12, 'eventId', 'evt_2628', '2025-11-11 13:21:55'),
(24, 12, 'docLink', 'https://docs.local/welcome/4901', '2025-11-11 13:21:55'),
(25, 13, 'workerId', 'w_1470', '2025-11-11 13:30:30'),
(26, 13, 'ticketId', 'INC3300', '2025-11-11 13:30:30'),
(27, 13, 'userId', 'u_8138', '2025-11-11 13:30:30'),
(28, 13, 'eventId', 'evt_9369', '2025-11-11 13:30:30'),
(29, 13, 'docLink', 'https://docs.local/welcome/7973', '2025-11-11 13:30:31'),
(30, 14, 'eventId', 'evt_5200', '2025-11-11 13:42:13'),
(31, 15, 'offerDoc', 'https://docs.local/welcome/9408', '2025-11-11 13:54:47'),
(32, 16, 'offerDoc', 'https://docs.local/welcome/9181', '2025-11-11 14:01:01'),
(33, 17, 'offerDoc', 'https://docs.local/welcome/6030', '2025-11-11 14:04:10'),
(34, 18, 'offerDoc', 'https://docs.local/welcome/5801', '2025-11-11 14:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `employee_name` varchar(100) DEFAULT NULL,
  `leave_type` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_email` varchar(255) NOT NULL,
  `employee_name` varchar(255) NOT NULL,
  `leave_type` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `manager_email` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_email`, `employee_name`, `leave_type`, `start_date`, `end_date`, `reason`, `manager_email`, `status`, `created_at`) VALUES
(1, 'pranav123@gmail.com', 'Pranav', 'Sick Leave', '2025-11-11', '2025-11-14', 'Sick', 'talentflowxyz@gmail.com', 'Approved', '2025-11-11 14:09:53');

-- --------------------------------------------------------

--
-- Table structure for table `runs`
--

CREATE TABLE `runs` (
  `id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `step` varchar(100) NOT NULL,
  `status` enum('ok','failed') NOT NULL,
  `message` text DEFAULT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `runs`
--

INSERT INTO `runs` (`id`, `task_id`, `step`, `status`, `message`, `data`, `created_at`) VALUES
(6, 3, 'HRIS.createWorker', 'ok', 'Worker created', '{\"workerId\":\"w_4232\"}', '2025-11-09 17:57:24'),
(7, 3, 'ITSM.createTicket', 'ok', 'Ticket created', '{\"ticketId\":\"INC4260\"}', '2025-11-09 17:57:24'),
(8, 3, 'IAM.createUser', 'ok', 'User created', '{\"userId\":\"u_2480\"}', '2025-11-09 17:57:24'),
(9, 3, 'Calendar.createEvent', 'ok', 'Orientation scheduled', '{\"eventId\":\"evt_7813\"}', '2025-11-09 17:57:24'),
(10, 3, 'Messaging.send', 'ok', 'Manager notified', NULL, '2025-11-09 17:57:24'),
(11, 7, 'Calendar.scheduleInterview', 'ok', 'Interview scheduled', '{\"eventId\":\"evt_4024\"}', '2025-11-09 18:05:29'),
(12, 7, 'Messaging.notifyPanel', 'ok', 'Interview panel notified', NULL, '2025-11-09 18:05:29'),
(13, 8, 'Docs.generateOfferLetter', 'ok', 'Offer document generated', '{\"doc\":\"https:\\/\\/docs.local\\/welcome\\/4987\"}', '2025-11-09 18:07:51'),
(14, 8, 'Messaging.sendOffer', 'ok', 'Offer sent to candidate', NULL, '2025-11-09 18:07:51'),
(15, 9, 'HRIS.createWorker', 'ok', 'Worker created', '{\"workerId\":\"w_9124\"}', '2025-11-09 18:38:32'),
(16, 9, 'ITSM.createTicket', 'ok', 'Access ticket created', '{\"ticketId\":\"INC8415\"}', '2025-11-09 18:38:32'),
(17, 9, 'IAM.createUser', 'ok', 'User created in IAM', '{\"userId\":\"u_6944\"}', '2025-11-09 18:38:32'),
(18, 9, 'Calendar.createEvent', 'ok', 'Orientation event scheduled', '{\"eventId\":\"evt_9922\"}', '2025-11-09 18:38:32'),
(19, 9, 'Messaging.send', 'ok', 'Manager notified about onboarding completion', NULL, '2025-11-09 18:38:32'),
(20, 10, 'Calendar.scheduleInterview', 'ok', 'Interview scheduled', '{\"eventId\":\"evt_1677\"}', '2025-11-09 18:39:56'),
(21, 10, 'Messaging.notifyPanel', 'ok', 'Interview panel notified', NULL, '2025-11-09 18:39:56'),
(22, 11, 'Docs.generateOfferLetter', 'ok', 'Offer document generated', '{\"doc\":\"https:\\/\\/docs.local\\/welcome\\/2117\"}', '2025-11-09 18:41:18'),
(23, 11, 'Messaging.sendOffer', 'ok', 'Offer sent to candidate', NULL, '2025-11-09 18:41:18'),
(24, 12, 'HRIS.createWorker', 'ok', 'Worker created', '{\"workerId\":\"w_3339\"}', '2025-11-11 13:21:55'),
(25, 12, 'ITSM.createTicket', 'ok', 'Access ticket created', '{\"ticketId\":\"INC8921\"}', '2025-11-11 13:21:55'),
(26, 12, 'IAM.createUser', 'ok', 'User created in IAM', '{\"userId\":\"u_3330\"}', '2025-11-11 13:21:55'),
(27, 12, 'Calendar.createEvent', 'ok', 'Orientation event scheduled', '{\"eventId\":\"evt_2628\"}', '2025-11-11 13:21:55'),
(28, 12, 'Messaging.send', 'ok', 'Manager notified about onboarding completion', NULL, '2025-11-11 13:21:55'),
(29, 13, 'HRIS.createWorker', 'ok', 'Worker created', '{\"workerId\":\"w_1470\"}', '2025-11-11 13:30:30'),
(30, 13, 'ITSM.createTicket', 'ok', 'Access ticket created', '{\"ticketId\":\"INC3300\"}', '2025-11-11 13:30:30'),
(31, 13, 'IAM.createUser', 'ok', 'User created in IAM', '{\"userId\":\"u_8138\"}', '2025-11-11 13:30:30'),
(32, 13, 'Calendar.createEvent', 'ok', 'Orientation event scheduled', '{\"eventId\":\"evt_9369\"}', '2025-11-11 13:30:30'),
(33, 13, 'Messaging.send', 'ok', 'Manager notified about onboarding completion', NULL, '2025-11-11 13:30:31'),
(34, 14, 'Calendar.scheduleInterview', 'ok', 'Interview scheduled', '{\"eventId\":\"evt_5200\"}', '2025-11-11 13:42:13'),
(35, 14, 'Messaging.notifyPanel', 'ok', 'Interview panel notified', NULL, '2025-11-11 13:42:13'),
(36, 15, 'Docs.generateOfferLetter', 'ok', 'Offer document generated', '{\"doc\":\"https:\\/\\/docs.local\\/welcome\\/9408\"}', '2025-11-11 13:54:47'),
(37, 15, 'Messaging.sendOffer', 'ok', 'Offer sent to candidate', NULL, '2025-11-11 13:54:47'),
(38, 16, 'Docs.generateOfferLetter', 'ok', 'Offer document generated', '{\"doc\":\"https:\\/\\/docs.local\\/welcome\\/9181\"}', '2025-11-11 14:01:01'),
(39, 16, 'Messaging.sendOffer', 'ok', 'Offer sent to candidate', NULL, '2025-11-11 14:01:01'),
(40, 17, 'Docs.generateOfferLetter', 'ok', 'Offer document generated', '{\"doc\":\"https:\\/\\/docs.local\\/welcome\\/6030\"}', '2025-11-11 14:04:10'),
(41, 17, 'Messaging.sendOffer', 'ok', 'Offer sent to candidate', NULL, '2025-11-11 14:04:10'),
(42, 18, 'Docs.generateOfferLetter', 'ok', 'Offer document generated', '{\"doc\":\"https:\\/\\/docs.local\\/welcome\\/5801\"}', '2025-11-11 14:04:19'),
(43, 18, 'Messaging.sendOffer', 'ok', 'Offer sent to candidate', NULL, '2025-11-11 14:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `type` enum('onboarding','interview','offer','faq') NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`payload`)),
  `status` enum('pending','running','done','error') DEFAULT 'pending',
  `user_email` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`id`, `type`, `payload`, `status`, `user_email`, `created_at`) VALUES
(3, 'onboarding', '{\"name\":\"ARUN VASUDEVAN\",\"role\":\"Data Analyst\",\"start_date\":\"2025-11-10\",\"manager_email\":\"suresh@company.com\",\"location\":\"Bengaluru\",\"email\":\"arun737@gmail.com\",\"bundle\":\"DataAnalyst\"}', 'done', 'pranav123@gmail.com', '2025-11-09 17:57:24'),
(4, 'interview', '{\"candidate\":\"Sangeeth\",\"role\":\"Data Analyst\",\"panel\":\"talentflow1@gmail.com\",\"date\":\"2025-11-11\"}', 'done', 'pranav123@gmail.com', '2025-11-09 17:59:41'),
(7, 'interview', '{\"candidate\":\"Sangeeth\",\"role\":\"Data Analyst\",\"panel\":\"talentflow1@gmail.com\",\"date\":\"2025-11-11\"}', 'done', 'pranav123@gmail.com', '2025-11-09 18:05:29'),
(8, 'offer', '{\"candidate\":\"Jithin\",\"role\":\"Django Developer\",\"ctc\":\"40000\",\"start_date\":\"2025-11-10\"}', 'done', 'pranav123@gmail.com', '2025-11-09 18:07:51'),
(9, 'onboarding', '{\"name\":\"Krishna\",\"role\":\"Full stack developer\",\"start_date\":\"2025-11-12\",\"manager_email\":\"sangeeth@company.com\",\"location\":\"Bengaluru\",\"email\":\"krishna1323@gmail.com\",\"bundle\":\"Engineer\"}', 'done', 'sangeethsanthosh123@gmail.com', '2025-11-09 18:38:32'),
(10, 'interview', '{\"candidate\":\"Jithin\",\"role\":\"Developer\",\"panel\":\"talentflow1@gmail.com\",\"date\":\"2025-11-11\"}', 'done', 'sangeethsanthosh123@gmail.com', '2025-11-09 18:39:56'),
(11, 'offer', '{\"candidate\":\"Hari\",\"role\":\"Django Developer\",\"ctc\":\"40000\",\"start_date\":\"2025-11-11\"}', 'done', 'sangeethsanthosh123@gmail.com', '2025-11-09 18:41:18'),
(12, 'onboarding', '{\"name\":\"Pranav Eswar\",\"role\":\"Data Analyst\",\"start_date\":\"2025-11-12\",\"manager_email\":\"talentflowxyz@gmail.com\",\"location\":\"Kochi\",\"email\":\"pranavartist1@gmail.com\",\"bundle\":\"DataAnalyst\"}', 'done', 'pranav123@gmail.com', '2025-11-11 13:21:55'),
(13, 'onboarding', '{\"name\":\"Pranav Eswar\",\"role\":\"Data Analyst\",\"start_date\":\"2025-11-11\",\"manager_email\":\"talentflowxyz@gmail.com\",\"location\":\"Bengaluru\",\"email\":\"pranavartist1@gmail.com\",\"bundle\":\"HRGeneralist\"}', 'done', 'pranav123@gmail.com', '2025-11-11 13:30:30'),
(14, 'interview', '{\"candidate\":\"Pranav\",\"role\":\"Developer\",\"panel\":\"pranavartist1@gmail.com\",\"date\":\"2025-11-12\"}', 'done', 'pranav123@gmail.com', '2025-11-11 13:42:13'),
(15, 'offer', '{\"candidate\":\"Pranav\",\"email\":\"pranavartist1@gmail.com\",\"role\":\"FRONTEND DEVELOPER\",\"ctc\":\"12,00,000\",\"start_date\":\"2025-11-12\"}', 'done', 'pranav123@gmail.com', '2025-11-11 13:54:47'),
(16, 'offer', '{\"candidate\":\"Prathap\",\"email\":\"pranaveswar60@gmail.com\",\"role\":\"Data Analyst\",\"ctc\":\"800000\",\"start_date\":\"2025-11-13\"}', 'done', 'pranav123@gmail.com', '2025-11-11 14:01:01'),
(17, 'offer', '{\"candidate\":\"Pranav\",\"email\":\"pranaveswar60@gmail.com\",\"role\":\"Django Developer\",\"ctc\":\"500000\",\"start_date\":\"2025-11-12\"}', 'done', 'pranav123@gmail.com', '2025-11-11 14:04:10'),
(18, 'offer', '{\"candidate\":\"Pranav\",\"email\":\"pranaveswar60@gmail.com\",\"role\":\"Django Developer\",\"ctc\":\"500000\",\"start_date\":\"2025-11-12\"}', 'done', 'pranav123@gmail.com', '2025-11-11 14:04:19');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Pranav Eswar', 'pranav123@gmail.com', '$2y$10$vfyerdyQoMgUqMeGqM6XtOI4yitArb3zbtGytzNspIW1S.eHYUs5e', '2025-11-09 17:48:12'),
(2, 'Akash B', 'akash123@gmail.com', '$2y$10$mC1ZQrPfMAMfkmBJQyTvwuzlZUjZS5rshZeJj1ujrf.sZYXvc9S5i', '2025-11-09 17:49:43'),
(3, 'Sangeeth', 'sangeethsanthosh123@gmail.com', '$2y$10$y3xOOgAX2jJx0o2ibYls2.OeI2XzIb2cjxrSC.7RPXePOWAe4xv..', '2025-11-09 18:36:28'),
(4, 'ARUN VASUDEVAN', 'arun737@gmail.com', '$2y$10$jvPWPH1lGoRzZVXiFxbhu.FFJ3iw6rbjGdoThfoao5vct4bRGGrva', '2025-11-11 16:15:54');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `artifacts`
--
ALTER TABLE `artifacts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `runs`
--
ALTER TABLE `runs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `task_id` (`task_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `artifacts`
--
ALTER TABLE `artifacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `runs`
--
ALTER TABLE `runs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `artifacts`
--
ALTER TABLE `artifacts`
  ADD CONSTRAINT `artifacts_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `runs`
--
ALTER TABLE `runs`
  ADD CONSTRAINT `runs_ibfk_1` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
