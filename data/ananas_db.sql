-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 22, 2026 lúc 08:42 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ananas_db`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart`
--

CREATE TABLE `cart` (
  `idCart` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `idSP` int(11) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 1,
  `ngaythem` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `idOrder` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `hoten` varchar(150) NOT NULL,
  `sdt` varchar(20) NOT NULL,
  `diachi` varchar(255) NOT NULL,
  `ghichu` text DEFAULT NULL,
  `tongtien` bigint(20) NOT NULL,
  `trangthai` varchar(30) NOT NULL DEFAULT 'Chờ xử lý',
  `ngaydat` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_details`
--

CREATE TABLE `order_details` (
  `idDetail` int(11) NOT NULL,
  `idOrder` int(11) NOT NULL,
  `idSP` int(11) NOT NULL,
  `soluong` int(11) NOT NULL,
  `dongia` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `idPost` int(11) NOT NULL,
  `tieude` varchar(200) NOT NULL,
  `noidung` text NOT NULL,
  `hinhanh` varchar(255) DEFAULT NULL,
  `tacgia` varchar(50) NOT NULL,
  `ngaydang` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`idPost`, `tieude`, `noidung`, `hinhanh`, `tacgia`, `ngaydang`) VALUES
(1, 'Cách chọn giày sneaker phù hợp với dáng chân', 'Việc lựa chọn một đôi sneaker phù hợp không chỉ dựa vào kiểu dáng đẹp mà còn cần xem xét form chân, chất liệu và mục đích sử dụng...', 'post/post1.jpg', 'admin', '2026-08-27 11:25:52'),
(2, 'Xu hướng giày Boots mùa đông 2026', 'Giày Boots đang trở lại mạnh mẽ trong mùa đông năm nay với nhiều thiết kế phá cách...', 'post/post2.jpg', 'admin', '2026-08-27 11:25:52'),
(3, 'Hướng dẫn bảo quản giày da đúng cách', 'Giày da cần được vệ sinh và bảo quản đúng cách để giữ được độ bền và thẩm mỹ theo thời gian...', 'post/post3.jpg', 'admin', '2026-08-27 11:25:52'),
(4, 'Top 5 mẫu giày sneaker đáng sở hữu nhất năm 2026', 'Bạn đang tìm kiếm một đôi sneaker vừa đẹp, vừa dễ phối đồ? Khám phá 5 mẫu giày thể thao nổi bật trong năm 2026, từ Adidas Samba mang phong cách retro cổ điển đến New Balance 574 nổi tiếng với sự êm ái và linh hoạt. Mỗi thiết kế đều sở hữu nét cuốn hút riêng, giúp bạn tự tin thể hiện cá tính dù theo đuổi phong cách năng động, tối giản hay streetwear hiện đại. Cùng khám phá và tìm ra đôi giày phù hợp nhất với phong cách của bạn!', 'post/post4.jpg', 'admin', '2026-09-22 11:47:48'),
(5, 'Bí quyết phối đồ với boots: Cá tính, thời thượng trong mùa lạnh', 'Boots luôn là món đồ không thể thiếu trong tủ giày của những tín đồ thời trang mỗi khi mùa lạnh ghé đến. Không chỉ giúp giữ ấm, một đôi boots phù hợp còn có thể biến set đồ đơn giản trở nên nổi bật và đầy cá tính. Từ combat boots mạnh mẽ khi kết hợp cùng quần jeans, áo khoác da đến ankle boots thanh lịch đi cùng chân váy hay áo len, bài viết sẽ gợi ý những cách phối đồ dễ áp dụng, giúp bạn tự tin tạo dấu ấn riêng trong mọi hoàn cảnh.', 'post/post5.jpg', 'admin', '2026-09-22 11:47:48'),
(6, 'Hướng dẫn vệ sinh giày sneaker tại nhà: Sạch đẹp như mới', 'Một đôi sneaker sạch sẽ không chỉ giúp bạn tự tin hơn mà còn góp phần giữ gìn vẻ đẹp và kéo dài tuổi thọ của giày. Tuy nhiên, nếu vệ sinh sai cách, đặc biệt với sneaker trắng, bạn có thể vô tình khiến giày bị ố vàng, bạc màu hoặc hư hỏng chất liệu. Trong bài viết này, chúng tôi sẽ hướng dẫn bạn các bước làm sạch giày đơn giản ngay tại nhà, từ xử lý vết bẩn trên thân giày, vệ sinh đế cao su đến cách phơi và bảo quản đúng cách. Chỉ với một chút thời gian và sự chăm sóc phù hợp, đôi sneaker yêu thích của bạn sẽ luôn sạch đẹp và sẵn sàng đồng hành trên mọi hành trình.', 'post/post6.jpg', 'admin', '2026-09-22 11:47:48'),
(7, 'Giới thiệu Shoe Store NDT - Điểm đến cho tín đồ giày', 'Shoe Store NDT ra đời với mong muốn mang đến những đôi giày chất lượng, đa dạng phong cách từ sneaker năng động đến boots cá tính. Chúng tôi chọn lọc kỹ càng từng sản phẩm để đảm bảo khách hàng luôn hài lòng cả về chất lượng lẫn giá thành.', 'post/post7.svg\r\n', 'admin', '2026-09-22 12:30:42'),
(8, 'Cam kết chất lượng tại Shoe Store NDT', 'Mỗi sản phẩm tại cửa hàng đều được kiểm tra kỹ trước khi đến tay khách hàng. Chúng tôi cam kết hàng chính hãng, đường may chắc chắn, chất liệu bền bỉ theo thời gian, cùng chính sách đổi trả rõ ràng để khách hàng an tâm mua sắm.', 'post/post8.svg', 'admin', '2026-09-22 12:30:42'),
(9, 'Vì sao nên chọn mua giày tại Shoe Store NDT', 'Không chỉ đa dạng mẫu mã, Shoe Store NDT còn chú trọng trải nghiệm mua sắm: giao diện dễ dùng, tư vấn nhiệt tình, giao hàng nhanh chóng và hỗ trợ đổi trả linh hoạt. Đó là lý do ngày càng nhiều khách hàng tin tưởng lựa chọn chúng tôi.', 'post/post9.svg', 'admin', '2026-09-22 12:30:42');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `idSP` int(11) NOT NULL,
  `tengiay` varchar(150) NOT NULL,
  `gia` int(11) NOT NULL,
  `hinhanh` varchar(255) NOT NULL,
  `mota` text DEFAULT NULL,
  `idType` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`idSP`, `tengiay`, `gia`, `hinhanh`, `mota`, `idType`) VALUES
(1, 'Converse High Top', 610000, 'giay1/a.jpg', 'Thiết kế cao cổ mạnh mẽ', 1),
(2, 'Strike Boot Baleciaga', 30000000, 'giay11/a.jpg', 'Dòng giày phong cách hiện đại phong nhã', 2),
(3, 'Rick Owens High Top', 580000, 'giay3/a.jpg', 'Dòng Basas với đế Gum đặc trưng', 1),
(4, 'Timberland', 490000, 'giay4/a.jpg', 'Phong cách quý ông thanh lịch', 2),
(5, 'Adidas Samba', 650000, 'giay5/a.jpg', 'Màu sắc phá cách cho giới trẻ', 1),
(6, 'Adidas Superstar', 620000, 'giay6/a.jpg', 'Họa tiết chấm bi độc đáo', 1),
(7, 'MMY', 650000, 'giay7/a.jpg', 'Phiên bản thấp cổ đế Gum', 1),
(9, 'Vans', 500000, 'giay8/a.jpg', 'Độc lạ bền bỉ', 1),
(12, 'New Balance 574', 123456, 'giay9/a.jpg', 'cổ điển hiện đại và hại điện', 1),
(13, 'Onitsuka Tiger66', 890000, 'giay2/a.jpg', 'Thiết kế retro Nhật Bản, form giày ôm chân, phù hợp phối đồ streetwear', 1),
(14, 'Nike Low', 950000, 'giay10/a.jpg', 'Kiểu dáng thấp cổ năng động, đế êm, thích hợp mang hằng ngày', 1),
(15, 'Dr. Martens 1460', 2450000, 'giay12/a.jpg', 'Đôi boots kinh điển 8 lỗ xỏ dây, da bền bỉ, đế AirWair êm chân, phong cách bụi bặm', 2),
(16, 'Rhinestone Stiletto Ankle', 1590000, 'giay13/a.jpg', 'Ankle boots gót nhọn đính đá lấp lánh, tôn dáng, phù hợp dạo phố và dự tiệc', 2),
(17, 'Chunky Platform Combat', 1780000, 'giay14/a.jpg', 'Combat boots đế platform dày cá tính, phong cách grunge/streetwear', 2),
(18, 'Rick Owens Tubro Cyclops Lace', 3200000, 'giay15/a.jpg', 'Đôi boots avant-garde với thiết kế đế Cyclops đặc trưng, form dáng phá cách đậm chất thời trang cao cấp, phù hợp với người yêu phong cách thời trang thử nghiệm', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `idImage` int(11) NOT NULL,
  `idSP` int(11) NOT NULL,
  `tenAnh` varchar(255) NOT NULL,
  `thutu` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`idImage`, `idSP`, `tenAnh`, `thutu`) VALUES
(1, 1, 'giay1/b.jpg', 0),
(2, 1, 'giay1/c.jpg', 1),
(3, 1, 'giay1/d.jpg', 2),
(4, 13, 'giay2/b.jpg', 0),
(5, 13, 'giay2/c.jpg', 1),
(6, 13, 'giay2/d.jpg', 2),
(7, 3, 'giay3/b.jpg', 0),
(8, 3, 'giay3/c.jpg', 1),
(9, 3, 'giay3/d.jpg', 2),
(10, 4, 'giay4/b.jpg', 0),
(11, 4, 'giay4/c.jpg', 1),
(12, 4, 'giay4/d.jpg', 2),
(13, 5, 'giay5/b.jpg', 0),
(14, 5, 'giay5/c.jpg', 1),
(15, 5, 'giay5/d.jpg', 2),
(16, 6, 'giay6/b.jpg', 0),
(17, 6, 'giay6/c.jpg', 1),
(18, 6, 'giay6/d.jpg', 2),
(19, 7, 'giay7/b.jpg', 0),
(20, 7, 'giay7/c.jpg', 1),
(21, 7, 'giay7/d.jpg', 2),
(22, 9, 'giay8/b.jpg', 0),
(23, 9, 'giay8/c.jpg', 1),
(24, 9, 'giay8/d.jpg', 2),
(25, 12, 'giay9/b.jpg', 0),
(26, 12, 'giay9/c.jpg', 1),
(27, 12, 'giay9/d.jpg', 2),
(28, 14, 'giay10/b.jpg', 0),
(29, 14, 'giay10/c.jpg', 1),
(30, 14, 'giay10/d.jpg', 2),
(31, 2, 'giay11/b.jpg', 0),
(32, 2, 'giay11/c.jpg', 1),
(33, 2, 'giay11/d.jpg', 2),
(64, 15, 'giay12/b.jpg', 0),
(65, 15, 'giay12/c.jpg', 1),
(66, 15, 'giay12/d.jpg', 2),
(67, 16, 'giay13/b.jpg', 0),
(68, 16, 'giay13/c.jpg', 1),
(69, 16, 'giay13/d.jpg', 2),
(70, 17, 'giay14/b.jpg', 0),
(71, 17, 'giay14/c.jpg', 1),
(72, 17, 'giay14/d.jpg', 2),
(79, 18, 'giay15/b.jpg', 0),
(80, 18, 'giay15/c.jpg', 1),
(81, 18, 'giay15/d.jpg', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_sizes`
--

CREATE TABLE `product_sizes` (
  `idPSize` int(11) NOT NULL,
  `idSP` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `soluong` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `product_sizes`
--

INSERT INTO `product_sizes` (`idPSize`, `idSP`, `size`, `soluong`) VALUES
(1, 1, 36, 10),
(2, 1, 37, 10),
(3, 1, 38, 10),
(4, 1, 39, 10),
(5, 1, 40, 10),
(6, 1, 41, 10),
(7, 1, 42, 10),
(8, 1, 43, 10),
(9, 1, 44, 10),
(19, 3, 36, 10),
(20, 3, 37, 10),
(21, 3, 38, 10),
(22, 3, 39, 10),
(23, 3, 40, 10),
(24, 3, 41, 10),
(25, 3, 42, 10),
(26, 3, 43, 10),
(27, 3, 44, 10),
(28, 4, 36, 10),
(29, 4, 37, 10),
(30, 4, 38, 10),
(31, 4, 39, 10),
(32, 4, 40, 10),
(33, 4, 41, 10),
(34, 4, 42, 10),
(35, 4, 43, 10),
(36, 4, 44, 10),
(37, 5, 36, 10),
(38, 5, 37, 10),
(39, 5, 38, 10),
(40, 5, 39, 10),
(41, 5, 40, 10),
(42, 5, 41, 10),
(43, 5, 42, 10),
(44, 5, 43, 10),
(45, 5, 44, 10),
(64, 9, 36, 10),
(65, 9, 37, 10),
(66, 9, 38, 10),
(67, 9, 39, 10),
(68, 9, 40, 10),
(69, 9, 41, 10),
(70, 9, 42, 10),
(71, 9, 43, 10),
(72, 9, 44, 10),
(73, 12, 36, 10),
(74, 12, 37, 10),
(75, 12, 38, 10),
(76, 12, 39, 10),
(77, 12, 40, 10),
(78, 12, 41, 10),
(79, 12, 42, 10),
(80, 12, 43, 10),
(81, 12, 44, 10),
(82, 13, 36, 10),
(83, 13, 37, 10),
(84, 13, 38, 10),
(85, 13, 39, 10),
(86, 13, 40, 10),
(87, 13, 41, 10),
(88, 13, 42, 10),
(89, 13, 43, 10),
(90, 13, 44, 10),
(192, 14, 36, 0),
(193, 14, 37, 0),
(194, 14, 38, 0),
(195, 14, 39, 0),
(196, 14, 40, 0),
(197, 14, 41, 0),
(198, 14, 42, 0),
(199, 14, 43, 0),
(200, 14, 44, 0),
(210, 7, 36, 0),
(211, 7, 37, 0),
(212, 7, 38, 0),
(213, 7, 39, 0),
(214, 7, 40, 0),
(215, 7, 41, 0),
(216, 7, 42, 0),
(217, 7, 43, 0),
(218, 7, 44, 0),
(219, 6, 36, 0),
(220, 6, 37, 0),
(221, 6, 38, 0),
(222, 6, 39, 0),
(223, 6, 40, 0),
(224, 6, 41, 0),
(225, 6, 42, 0),
(226, 6, 43, 0),
(227, 6, 44, 0),
(228, 2, 36, 0),
(229, 2, 37, 0),
(230, 2, 38, 0),
(231, 2, 39, 0),
(232, 2, 40, 2),
(233, 2, 41, 2),
(234, 2, 42, 2),
(235, 2, 43, 2),
(236, 2, 44, 2),
(237, 18, 36, 0),
(238, 18, 37, 0),
(239, 18, 38, 0),
(240, 18, 39, 0),
(241, 18, 40, 0),
(242, 18, 41, 3),
(243, 18, 42, 2),
(244, 18, 43, 0),
(245, 18, 44, 1),
(246, 17, 36, 3),
(247, 17, 37, 4),
(248, 17, 38, 0),
(249, 17, 39, 2),
(250, 17, 40, 1),
(251, 17, 41, 0),
(252, 17, 42, 0),
(253, 17, 43, 0),
(254, 17, 44, 1),
(255, 16, 36, 0),
(256, 16, 37, 0),
(257, 16, 38, 3),
(258, 16, 39, 2),
(259, 16, 40, 0),
(260, 16, 41, 1),
(261, 16, 42, 1),
(262, 16, 43, 0),
(263, 16, 44, 0),
(264, 15, 36, 5),
(265, 15, 37, 5),
(266, 15, 38, 5),
(267, 15, 39, 5),
(268, 15, 40, 5),
(269, 15, 41, 5),
(270, 15, 42, 5),
(271, 15, 43, 5),
(272, 15, 44, 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role`
--

CREATE TABLE `role` (
  `IDRole` int(11) NOT NULL,
  `TenRole` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `role`
--

INSERT INTO `role` (`IDRole`, `TenRole`) VALUES
(0, 'Khách hàng'),
(1, 'Admin (Quản trị viên)'),
(2, 'Staff (Nhân viên)');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `typeproducts`
--

CREATE TABLE `typeproducts` (
  `idType` int(11) NOT NULL,
  `typeName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `typeproducts`
--

INSERT INTO `typeproducts` (`idType`, `typeName`) VALUES
(1, 'Sneaker'),
(2, 'Boots'),
(3, 'Sandal');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `userr`
--

CREATE TABLE `userr` (
  `Username` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `IDRole` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `userr`
--

INSERT INTO `userr` (`Username`, `Password`, `IDRole`) VALUES
('abc', '', 0),
('admin', '123456', 1),
('dienthinh28', '123456', 0),
('nhatvinh99', '123456', 0),
('staff1', '123456', 2);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`idCart`),
  ADD KEY `fk_cart_user` (`Username`),
  ADD KEY `fk_cart_product` (`idSP`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`idOrder`),
  ADD KEY `fk_order_user` (`Username`);

--
-- Chỉ mục cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`idDetail`),
  ADD KEY `fk_detail_order` (`idOrder`),
  ADD KEY `fk_detail_product` (`idSP`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`idPost`),
  ADD KEY `fk_post_user` (`tacgia`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`idSP`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`idImage`),
  ADD KEY `fk_image_product` (`idSP`);

--
-- Chỉ mục cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD PRIMARY KEY (`idPSize`),
  ADD UNIQUE KEY `uniq_sp_size` (`idSP`,`size`),
  ADD KEY `fk_size_product` (`idSP`);

--
-- Chỉ mục cho bảng `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`IDRole`);

--
-- Chỉ mục cho bảng `typeproducts`
--
ALTER TABLE `typeproducts`
  ADD PRIMARY KEY (`idType`);

--
-- Chỉ mục cho bảng `userr`
--
ALTER TABLE `userr`
  ADD PRIMARY KEY (`Username`),
  ADD KEY `fk_userr_role` (`IDRole`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cart`
--
ALTER TABLE `cart`
  MODIFY `idCart` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `idOrder` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_details`
--
ALTER TABLE `order_details`
  MODIFY `idDetail` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `idPost` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `idSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `idImage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  MODIFY `idPSize` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=273;

--
-- AUTO_INCREMENT cho bảng `typeproducts`
--
ALTER TABLE `typeproducts`
  MODIFY `idType` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`idSP`) REFERENCES `products` (`idSP`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`Username`) REFERENCES `userr` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_order_user` FOREIGN KEY (`Username`) REFERENCES `userr` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `fk_detail_order` FOREIGN KEY (`idOrder`) REFERENCES `orders` (`idOrder`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_product` FOREIGN KEY (`idSP`) REFERENCES `products` (`idSP`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `fk_post_user` FOREIGN KEY (`tacgia`) REFERENCES `userr` (`Username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_image_product` FOREIGN KEY (`idSP`) REFERENCES `products` (`idSP`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `product_sizes`
--
ALTER TABLE `product_sizes`
  ADD CONSTRAINT `fk_size_product` FOREIGN KEY (`idSP`) REFERENCES `products` (`idSP`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `userr`
--
ALTER TABLE `userr`
  ADD CONSTRAINT `fk_userr_role` FOREIGN KEY (`IDRole`) REFERENCES `role` (`IDRole`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
