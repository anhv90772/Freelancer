-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 08, 2024 at 10:53 PM
-- Server version: 10.3.39-MariaDB-cll-lve
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mxnhcsinhthanh_demo2`
--

DELIMITER $$
--
-- Procedures
--
$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `field` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `details` text CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `skills` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_vietnamese_ci NOT NULL,
  `deadline` date NOT NULL,
  `salary_from` varchar(555) NOT NULL,
  `salary_to` varchar(555) NOT NULL,
  `duyet` varchar(555) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `job_title`, `field`, `details`, `skills`, `deadline`, `salary_from`, `salary_to`, `duyet`, `created_at`, `user_id`) VALUES
(3, 'Ú Òa', 'Công nghệ thông tin', 'Ú Òa', 'Ú Òa', '2222-02-22', '222222', '222222', '0', '2024-10-06 04:01:00', 3),
(4, 'ijasijdo', 'Công nghệ thông tin', 'Ú Òa', 'Ú Òa', '2222-02-22', '222222', '222222', '0', '2024-10-06 04:01:00', 3),
(5, 'thiết kế web', 'Công nghệ thông tin', 'Thiết kế web tìm việc làm', 'html, css, javascrip, CSDL', '2024-10-10', '300000', '600000', '1', '2024-10-07 06:20:23', 5),
(6, 'Dịch văn bản Việt - Anh', 'Dịch thuật', 'Cần tuyển một bạn có kinh nghiệm dịch thuật để dịch hợp đồng kinh tế từ tiếng Việt ra tiếng Anh. Yêu cầu có bằng đại học chuyên ngành tiếng Anh hoặc chứng chỉ tiếng Anh TOEFL, IELTS, TOEIC, CEFR...', 'Có  chứng chỉ tiếng anh', '2024-08-10', '200000', '1000000', '1', '2024-10-07 16:46:41', 5),
(7, 'Thiết Kế Nhận Diện Thương Hiệu', 'Thiết kế', 'Tìm kiếm ý tưởng thiết kế sáng tạo cho bộ nhận diện thương hiệu mới của công ty Hakago, bao gồm: logo, màu sắc chủ đạo, font chữ, và các ứng dụng thực tế.\r\n\r\n• Tạo dựng hình ảnh chuyên nghiệp, hiện đại và dễ nhận diện cho thương hiệu Hakago.', 'có đam mê và kinh nghiệm trong lĩnh vực thiết kế bộ nhận diện thương hiệu.', '2024-10-24', '600000', '2000000', '1', '2024-10-08 11:24:30', 15),
(8, 'Thiết Kế Nhận Diện Thương Hiệu', 'Thiết kế', 'Tìm kiếm ý tưởng thiết kế sáng tạo cho bộ nhận diện thương hiệu mới của công ty Hakago, bao gồm: logo, màu sắc chủ đạo, font chữ, và các ứng dụng thực tế.\r\n\r\n• Tạo dựng hình ảnh chuyên nghiệp, hiện đại và dễ nhận diện cho thương hiệu Hakago.', 'có đam mê và kinh nghiệm trong lĩnh vực thiết kế bộ nhận diện thương hiệu.', '2024-10-24', '600000', '2000000', '0', '2024-10-08 11:24:55', 15);

-- --------------------------------------------------------

--
-- Table structure for table `nganhang`
--

CREATE TABLE `nganhang` (
  `id` int(11) NOT NULL,
  `nganhang` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stk` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ctk` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `linkqr` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `nganhang`
--

INSERT INTO `nganhang` (`id`, `nganhang`, `stk`, `ctk`, `linkqr`) VALUES
(1, 'MBBANK', '704022010', 'HUYNH THI NGOC UOC', 'https://vietqr.co/api/generate/mb/704022010/HUYNH%20THI%20NGOC%20UOC/null?isMask=0&logo=1&style=2&bg=61&download');

-- --------------------------------------------------------

--
-- Table structure for table `proposals`
--

CREATE TABLE `proposals` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `project_cost` varchar(9999) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `completion_time` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `experience` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `project_plan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `contact_info` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `trungtuyen` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `proposals`
--

INSERT INTO `proposals` (`id`, `job_id`, `sender_id`, `receiver_id`, `project_cost`, `completion_time`, `experience`, `project_plan`, `contact_info`, `trungtuyen`, `created_at`) VALUES
(2, 6, 10, 5, '500000', '10/10/2024', 'Tôi có chứng chỉ IELTS ', '...', 'sdt: 0367787798', 0, '2024-10-07 17:18:32');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `image_link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin_phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `timelamviec` varchar(9999) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `title`, `description`, `image_link`, `admin_email`, `admin_phone`, `timelamviec`) VALUES
(1, 'Test tiêu đề', 'Mô tả test', 'https://example.com/image.jpg', 'admin@example.com', '0123456789', '9h sáng đến 9h tối');

-- --------------------------------------------------------

--
-- Table structure for table `ticket`
--

CREATE TABLE `ticket` (
  `id` int(11) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ticket`
--

INSERT INTO `ticket` (`id`, `phone`, `email`, `message`, `created_at`) VALUES
(1, '0708653781', 'chauthebaocoder@gmail.com', 'ssssssss', '2024-10-06 14:26:29'),
(2, '0708653781', 'chauthebaocoder@gmail.com', 'ssssssss', '2024-10-06 14:26:29');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `nghenghiep` varchar(555) NOT NULL,
  `mota` varchar(555) NOT NULL,
  `password` varchar(255) NOT NULL,
  `work_type` enum('lamviec','doanhnghiep') NOT NULL,
  `sodu` varchar(9999) DEFAULT NULL,
  `anhdaidien` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `phone`, `nghenghiep`, `mota`, `password`, `work_type`, `sodu`, `anhdaidien`) VALUES
(5, 'trangg', 'tragtran124@gmail.com', '0362561120', '', '', '$2y$10$9KB9Jy.rv6xqe38lJAIk5.iR3KarfDZt8/0SzZPZFuikEhl08uFxq', 'doanhnghiep', '0', 'uploads/wzgpih.png'),
(8, 'admin', 'admin@gmail.com', '0123456789', '', '', '$2y$10$uAdgSjN23snO3OXC/py33euimPDtoCRpE1PPMT9aIQkASArBA4OiC', 'doanhnghiep', '0', 'uploads/gcwojz.jpg'),
(10, 'Vanh', 'ttttrang241204@gmail.com', '0372021228', '', '', '$2y$10$3AS4E2hj5BxQ/cwWmvVms.XdlZ/4WPrp94H.ApibIC4B4d1L7ViMm', 'lamviec', '0', 'uploads/rhyjfx.png'),
(11, 'Demo', 'Demo@gmail.com', '0127127171', '', '', '$2y$10$0JX7Ya9yJscHhp224iuGWeBQ9oO5ohud4RSiSCAUTXSXtSoczYlyK', 'lamviec', '0', 'uploads/iwuosy.jpg'),
(12, 'freelancer', 'freelancer@gmail.com', '0328137712', '', '', '$2y$10$dhR/r.7O9OVH6S4dUt4tOuV9AveM29LGX/Cv5/PKBJ9Ig9j22gYhC', 'lamviec', '0', 'uploads/euptij.jpg'),
(14, 'Chị Trang', 'chitrangmaidinh@gmail.com', '0987654321', '', '', '$2y$10$xJlW6w/f.RrrSiU5Br3FFOkAHEs09H7wdXjH1tk59oEgHncb0nFvm', 'doanhnghiep', '0', 'uploads/dfakpe.png'),
(15, 'Kiều Minh Tuấn', 'minhtuan@gmail.com', '0223334444', 'Bán hàng', '', '$2y$10$OKM5dmCaYHNgCBEsWBEL5..Bf43QGy3pzhbdDK7Tunjl.N22./dOC', 'doanhnghiep', '0', 'uploads/ivtaob.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nganhang`
--
ALTER TABLE `nganhang`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `proposals`
--
ALTER TABLE `proposals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket`
--
ALTER TABLE `ticket`
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
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `nganhang`
--
ALTER TABLE `nganhang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `proposals`
--
ALTER TABLE `proposals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket`
--
ALTER TABLE `ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `proposals`
--
ALTER TABLE `proposals`
  ADD CONSTRAINT `proposals_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`),
  ADD CONSTRAINT `proposals_ibfk_2` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `proposals_ibfk_3` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
