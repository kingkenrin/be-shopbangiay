-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 06, 2024 at 09:35 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shopbangiay`
--

-- --------------------------------------------------------

--
-- Table structure for table `bannershop`
--

CREATE TABLE `bannershop` (
  `bannerId` int(11) NOT NULL,
  `link` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `bannershop`
--

INSERT INTO `bannershop` (`bannerId`, `link`) VALUES
(1, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173945/shopbangiayuit/red%20%282%29.jpg.jpg'),
(2, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1730913810/shopbangiayuit/red%20main.jpg.jpg'),
(3, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173945/shopbangiayuit/red%20%282%29.jpg.jpg'),
(4, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1730913810/shopbangiayuit/red%20main.jpg.jpg'),
(6, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173945/shopbangiayuit/red%20%282%29.jpg.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cartId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `size` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cartId`, `userId`, `productId`, `quantity`, `size`) VALUES
(6, 32, 12, 10, 20),
(9, 32, 18, 6, 30),
(10, 33, 18, 3, 30),
(11, 32, 19, 3, 25);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`categoryId`, `name`) VALUES
(2, 'Giày thể thao'),
(3, 'Giày cao gót');

-- --------------------------------------------------------

--
-- Table structure for table `detailinvoice`
--

CREATE TABLE `detailinvoice` (
  `detailInvoiceId` int(11) NOT NULL,
  `invoiceId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `detailinvoice`
--

INSERT INTO `detailinvoice` (`detailInvoiceId`, `invoiceId`, `productId`, `size`, `quantity`) VALUES
(9, 5, 12, 20, 2),
(10, 5, 12, 25, 4),
(11, 6, 12, 20, 2),
(12, 6, 12, 25, 4),
(15, 8, 12, 20, 2),
(16, 8, 12, 25, 4),
(17, 9, 12, 20, 2),
(18, 9, 12, 25, 4),
(19, 10, 12, 20, 10),
(20, 10, 12, 25, 0),
(21, 11, 18, 20, 10),
(22, 11, 17, 25, 6),
(23, 12, 18, 20, 10),
(24, 12, 17, 25, 6),
(27, 14, 18, 20, 10),
(28, 14, 17, 25, 6),
(29, 15, 18, 20, 10),
(30, 15, 17, 25, 6),
(31, 16, 18, 20, 10),
(32, 16, 17, 25, 6),
(33, 17, 18, 20, 10),
(34, 17, 17, 25, 6),
(35, 18, 18, 20, 10),
(36, 18, 17, 25, 6),
(37, 19, 18, 20, 10),
(38, 19, 17, 25, 6);

-- --------------------------------------------------------

--
-- Table structure for table `detailproduct`
--

CREATE TABLE `detailproduct` (
  `detailProductId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `detailproduct`
--

INSERT INTO `detailproduct` (`detailProductId`, `productId`, `size`, `quantity`) VALUES
(21, 12, 20, 60),
(22, 12, 25, 50),
(31, 16, 20, 100),
(32, 16, 25, 100),
(33, 16, 30, 100),
(34, 16, 35, 100),
(35, 17, 20, 100),
(36, 17, 25, 46),
(37, 17, 30, 100),
(38, 17, 35, 100),
(39, 18, 20, 30),
(40, 18, 25, 100),
(41, 18, 30, 100),
(42, 18, 35, 100),
(43, 19, 20, 100),
(44, 19, 25, 100),
(45, 19, 30, 100),
(46, 19, 35, 100),
(47, 20, 20, 100),
(48, 20, 25, 100),
(49, 20, 30, 100),
(50, 20, 35, 100),
(51, 21, 20, 100),
(52, 21, 25, 100),
(53, 21, 30, 100),
(54, 21, 35, 100),
(55, 22, 20, 100),
(56, 22, 25, 100),
(57, 22, 30, 100),
(58, 22, 35, 100),
(59, 23, 20, 100),
(60, 23, 25, 100),
(61, 23, 30, 100),
(62, 23, 35, 100);

-- --------------------------------------------------------

--
-- Table structure for table `forgetpassword`
--

CREATE TABLE `forgetpassword` (
  `forgetPasswordId` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `code` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoiceId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `address` varchar(100) NOT NULL,
  `note` varchar(100) NOT NULL,
  `orderDate` varchar(100) NOT NULL,
  `state` enum('Pending','Shipping','Confirming','Cancel','Done') DEFAULT NULL,
  `totalPrice` double NOT NULL,
  `paymentMethod` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoiceId`, `userId`, `address`, `note`, `orderDate`, `state`, `totalPrice`, `paymentMethod`) VALUES
(6, 32, 'Gia Lai', 'thôi giao từ từ đi anh', '9/11/2024', 'Done', 0, ''),
(8, 32, 'Phu quoc', 'Giao le gium e', '9/11/2024', 'Pending', 0, ''),
(9, 32, 'Phu quoc', 'Giao le gium e', '9/11/2024', 'Pending', 12000000, ''),
(10, 32, 'Phu quoc', 'Giao le gium e', '9/11/2024', 'Pending', 20000000, ''),
(11, 33, 'Phu quoc', 'Giao le gium e', '10/11/2024', 'Pending', 14010000, ''),
(12, 33, 'Phu quoc', 'Giao le gium e', '6/12/2024', 'Pending', 14010000, ''),
(14, 33, 'Phu quoc', 'Giao le gium e', '6/12/2024', 'Pending', 14010000, ''),
(15, 33, 'Phu quoc', 'Giao le gium e', '7/12/2024', 'Pending', 14010000, ''),
(16, 33, 'Phu quoc', 'Giao le gium e', '7/12/2024', 'Pending', 14010000, ''),
(17, 33, 'Phu quoc', 'Giao le gium e', '7/12/2024', 'Pending', -63090000, ''),
(18, 33, 'Phu quoc', 'Giao le gium e', '7/12/2024', 'Pending', 13239000, ''),
(19, 33, 'Phu quoc', 'Giao le gium e', '7/12/2024', 'Pending', 13239000, 'momo');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `manufacturerId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`manufacturerId`, `name`) VALUES
(1, 'nike'),
(2, 'adidas'),
(3, 'bitis'),
(4, 'converse');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productId` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` double NOT NULL,
  `mainImage` varchar(100) DEFAULT NULL,
  `description` varchar(3000) NOT NULL,
  `manufacturerId` varchar(100) NOT NULL,
  `categoryId` varchar(100) NOT NULL,
  `discount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productId`, `name`, `price`, `mainImage`, `description`, `manufacturerId`, `categoryId`, `discount`) VALUES
(12, 'ao mua uit', 2000000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731001312/shopbangiayuit/blue%20main.png.png', 'ban muon co ao mua uit thi doi nam sau', '3', '2', 0),
(16, 'Giày Thể Thao Nam Biti’s Hunter HSM002900', 1305000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731206084/shopbangiayuit/cadoi%20main.png.png', 'Giới thiệu:\nMặc dù không quá cầu kỳ trong thời trang như phụ nữ nhưng phái mạnh cũng có một số phụ kiện thiết yếu trong tủ đồ của mình. Một item không thể thiếu của các chàng đó chính là đôi giày thể thao. Mẫu giày thể thao nam Biti’s Hunter HSM002900 có kiểu dáng rất hiện đại, trẻ trung, phù hợp cho đi chơi, dã ngoại và các hoạt động thể dục cho những tín đồ thời trang năng động. Mẫu giày cá tính này sẽ giúp các chàng trai khẳng định rõ nét phong cách cá nhân của chính mình.\n\nThông tin chi tiết: \n- Đế giày:\n+ Đế giày của HSM002900 là đế LITEFLEX 3.0 form dáng chunky gọn chân đàn hồi tốt\n+ Lót đế O Foam kháng khuẩn với chất liệu Ortholite, êm ái và hút ẩm hạn chế mùi tuyệt đối\n+ Đế giày rất nhẹ, êm ái, có nhiều rãnh chống trơn trượt tạo cho bạn cảm giác thoải mái, tự tin vận động\n+ Xung quanh giày được xử lý tạo độ cứng, tăng thêm lớp phủ giúp bảo vệ chân khỏi va chạm\n\n- Thân giày:\n+ Thân trên giày sở hữu những đường nét thiết kế đặc sắc, trẻ trung mạnh mẽ cùng màu sắc ấn tượng\n+ Giày được làm từ chất liệu vải dệt Knit thông hơi thoáng khí rất thoải mái\n+ Giày mềm mại, xung quanh có các lỗ thông khí, kháng khuẩn, tạo cảm giác dễ chịu nhất cho bạn dù mang cả ngày dài\n+ Sản phẩm với thiết kế cổ bo chun co giãn giúp bạn dễ dàng hơn khi mang\n+ Đôi giày sẽ giúp phái mạnh che đi ánh sáng trực tiếp từ mặt trời, giúp tránh bàn chân bị cháy nắng đỏ rát và tạo sự thoải mái cho các hoạt động ngoài trời', '3', '2', 0),
(17, 'Giày Thể Thao Nam Biti\'s Hunter X Wavy Collection HSM001400', 1050000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261322/shopbangiayuit/cadoi%20main.png.png', 'Giới thiệu:\nGiày Thể Thao Nam Biti\'s Hunter X Wavy Collection HSM là mẫu giày thể thao sở hữu vẻ đẹp hoàn hảo và bền chắc với bộ đế được sản xuất theo công nghệ độc quyền từ BITI’S Hunter. Từng đường kim mũi chỉ đều được trau chuốt tỉ mỉ đem lại sự bền chắc và tinh tế cho sản phẩm. Biti\'s Hunter X Wavy Collection HSM là lựa chọn đáng được bạn cân nhắc cho các môn thể thao chuyên cho chạy, đi bộ hoặc những hoạt động ngoài trời khác.\n\nThông tin chi tiết:\n- Đế giày:\n+ Dáng hình mô phỏng lốp xe mạnh mẽ, làm nổi bật lên vẻ đẹp cá tính dành cho người dùng.\n+ Chất liệu IP cao su nhẹ, hỗ trợ chiều cao lên đến > 5cm. Bên cạnh đó, người dùng sẽ được trải nghiệm bước đi nhẹ như bay với độ đàn hồi > 40%, dễ dàng di chuyển mọi địa hình và thời tiết.\n+ Lưới kháng khuẩn từ chất liệu Ortholite thun cá sấu, giúp ngăn mùi hiệu quả.\n+ Được ép khuôn 3D với công nghệ massage 6 điểm, vừa vặn, ôm trọn và nâng niu bàn chân không thua kém bất kỳ một thương hiệu nổi tiếng nào.\n+ Công nghệ đế hai lớp – tiếp đất êm, chống trượt hiệu quả, độ đàn hồi vượt trội >40%, giúp người dùng dễ dàng di chuyển.\n\n- Thân giày:\n+ Chất liệu: Sử dụng vải Liteknit với độ co giãn tốt, khả năng thoáng khí cao. Người dùng sẽ cảm giác thoải mái, dễ chịu khi mang.\n+ Quai Knits /Si Nubucks cấu trúc lớp co giãn, bề mặt mịn như nhung, khả năng chống mài mòn cao.\n+ Thiết kế đường Layer uốn lượn, tạo điểm nhấn sản phẩm.\n+ Phần hậu và má trong với chất liệu Mesh tăng thêm trải nghiệm từng bước chân.\n\n', '3', '2', 0),
(18, 'Giày Thể Thao Nam Biti\'s Hunter Core HSM000500', 771000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1730913810/shopbangiayuit/red%20main.jpg.jpg', 'Giới thiệu:\nGiày Thể Thao Nam Biti\'s Hunter Core HSM là dòng sản phẩm sở hữu thiết kế khỏe khoắn với bộ đế êm ái có khả năng đàn hồi cao. Giày hỗ trợ vận động nhẹ nhàng và cũng chính là đôi giày thời trang đang được yêu thích, sản phẩm được Biti’s chú trọng đến chất lượng và tập trung cao vào việc cải thiện cảm nhận người dùng. Thiết kế thời trang, phù hợp với xu hướng hiện đại Giày Thể Thao Nam Biti\'s Hunter Core HSM là sự lựa chọn hoàn hảo cho phái mạnh sử dụng cho các chuyến leo núi, trekking hay hoạt động ngoài trời khác.\n\nThông tin chi tiết:\n- Đế giày:\n+ Sản phẩm được sử dụng công nghệ đế Phylon cao su nhẹ độc quyền của Biti’s Hunter thế nên tạo được sự “nhẹ như bay”, ổn định cho đôi giày.\n+ Phần đế tiếp xúc sử dụng cao su kết hợp với các rãnh tạo độ ma sát, có độ bám dính cao, chống trơn trượt, khả năng chịu lực tốt, chống mài mòn và đem lại sự an toàn khi hoạt động nhất là trong những ngày trời mưa.\n+ Lót đế trong giúp ngăn mùi hiệu quả.\n+ Được ép khuôn 3D ôm trọn bàn chân, mang tới cảm giác êm ái, thoải mái và khả năng hỗ trợ trợ lực với bàn chân, tránh trượt, tụt gót, xê dịch và nâng niu bàn chân một cách tuyệt đối.\n+ Biti\'s Hunter Core HSM được thiết kế có phần đế giữa cao khoảng 3cm, thấp dần về phía mũi, mang nhiều đường cắt xẻ đem lại vẻ khỏe khoắn cho đế giày.\n+ Đế được làm từ chất liệu nhẹ, êm, mềm dẻo, chống nước, ép khuôn 3D bằng dây chuyền hiện đại tạo đàn hồi tốt, độ bền cao, hấp thu chất động, giảm ma sát, hỗ trợ lực và tạo độ êm ái đàn hồi khi di chuyển.\n\n- Thân giày:\n+ Chất liệu: Mũ quai dệt knits cấu trúc lớp co giãn nhằm tiết giảm chi tiết trên giày, hơn nữa khi sử dụng sẽ không bị nhăn và tạo nếp, không bí bách, thông thoáng khí, nhẹ, mát, dễ bảo quản và vệ sinh.\n+ Thiết kế: Sản phẩm có thiết kế trẻ trung, mạnh mẽ, đường Layer uốn lượn, tạo điểm nhấn cho giày.\n', '3', '2', 10),
(19, 'Chuck Taylor All Star Malden Street', 1260000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261559/shopbangiayuit/cadoi%20main.png.png', 'Những đôi giày mid-top mang tính biểu tượng này được nâng cấp với một diện mạo mới theo mùa. Đường may đa dạng gợi nhớ đến phong cách thủ công, trong khi đệm mềm mại mang lại sự thoải mái cho bước chân mùa hè của bạn.\n\nChi tiết:\n- Vải canvas cổ điển mang lại vẻ ngoài và cảm giác vượt thời gian của Chucks\n- Đệm OrthoLite giúp cung cấp sự thoải mái tối ưu\n- Lỗ xỏ dây bền, dệt chặt chẽ\n- Miếng vá Chuck Taylor mang tính biểu tượng ở mắt cá chân', '4', '2', 0),
(20, 'Chuck 70 Plus', 2500000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261667/shopbangiayuit/cadoi%20main.png.png', 'Một bản cập nhật bất ngờ trên một mẫu kinh điển mọi thời đại, Chuck 70 Plus kết hợp các đặc điểm mang tính biểu tượng với phong cách hướng tới tương lai. Sự kết hợp của vải canvas có trọng lượng khác nhau cùng với các đường nét táo bạo, không đối xứng tạo nên một diện mạo nổi bật. Các chi tiết cao su và miếng vá mắt cá chân được cắt ghép giữ mọi ánh nhìn tập trung vào bạn, trong khi đệm cao cấp giúp bạn cảm thấy nhẹ nhàng trên đôi chân.\n\nChi tiết:\n- Giày cao cổ với phần trên bằng vải canvas\n- Đệm OrthoLite mang lại sự thoải mái suốt cả ngày\n- Thiết kế không đối xứng, hợp nhất và lưỡi giày kéo dài tạo nên phong cách nổi bật\n- Đế cao su chia cắt làm nổi bật các yếu tố thiết kế đặc trưng của Chuck Taylor\n- Miếng vá mắt cá chân Chuck Taylor được thêm vào sản phẩm', '4', '2', 0),
(21, 'Star Player 76', 2500000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261737/shopbangiayuit/cadoi%20main.png.png', 'Làm mới phong cách của bạn với những đôi giày thể thao cổ thấp này. Chi tiết vải canvas và da bền bỉ mang lại phong cách cổ điển cho bước đi mùa hè của bạn, trong khi màu sắc táo bạo theo mùa thổi luồng sinh khí mới vào bất kỳ tủ đồ nào.\n\nChi tiết:\n- Vải canvas cổ điển mang lại vẻ ngoài và cảm giác vượt thời gian của Star Player\n- Đệm OrthoLite giúp cung cấp sự thoải mái tối ưu\n- Đế ngoài có hoa văn gạch đúc và mũi giày có kết cấu kim cương cổ điển\n- Biểu tượng Star Chevron bằng da mang tính biểu tượng', '4', '2', 0),
(22, 'heheheheh', 2500000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731001312/shopbangiayuit/blue%20main.png.png', 'Làm mới phong cách của bạn với những đôi giày thể thao cổ thấp này. Chi tiết vải canvas và da bền bỉ mang lại phong cách cổ điển cho bước đi mùa hè của bạn, trong khi màu sắc táo bạo theo mùa thổi luồng sinh khí mới vào bất kỳ tủ đồ nào.\n\nChi tiết:\n- Vải canvas cổ điển mang lại vẻ ngoài và cảm giác vượt thời gian của Star Player\n- Đệm OrthoLite giúp cung cấp sự thoải mái tối ưu\n- Đế ngoài có hoa văn gạch đúc và mũi giày có kết cấu kim cương cổ điển\n- Biểu tượng Star Chevron bằng da mang tính biểu tượng', '2', '2', 0),
(23, 'mingu', 2500000, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731001312/shopbangiayuit/blue%20main.png.png', 'Làm mới phong cách của bạn với những đôi giày thể thao cổ thấp này. Chi tiết vải canvas và da bền bỉ mang lại phong cách cổ điển cho bước đi mùa hè của bạn, trong khi màu sắc táo bạo theo mùa thổi luồng sinh khí mới vào bất kỳ tủ đồ nào.\n\nChi tiết:\n- Vải canvas cổ điển mang lại vẻ ngoài và cảm giác vượt thời gian của Star Player\n- Đệm OrthoLite giúp cung cấp sự thoải mái tối ưu\n- Đế ngoài có hoa văn gạch đúc và mũi giày có kết cấu kim cương cổ điển\n- Biểu tượng Star Chevron bằng da mang tính biểu tượng', '2', '2', 20);

-- --------------------------------------------------------

--
-- Table structure for table `productotherimage`
--

CREATE TABLE `productotherimage` (
  `productOtherImageId` int(11) NOT NULL,
  `productId` int(11) NOT NULL,
  `link` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `productotherimage`
--

INSERT INTO `productotherimage` (`productOtherImageId`, `productId`, `link`) VALUES
(22, 12, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731076705/shopbangiayuit/blue.jpg.jpg'),
(23, 12, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1730835578/shopbangiayuit/red%20%282%29.jpg.jpg'),
(32, 16, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731206087/shopbangiayuit/degiay.png.png'),
(33, 16, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731206090/shopbangiayuit/magiay.png.png'),
(34, 16, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731206092/shopbangiayuit/tronggiay.png.png'),
(35, 17, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261325/shopbangiayuit/degiay.png.png'),
(36, 17, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261328/shopbangiayuit/magiay.png.png'),
(37, 17, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261331/shopbangiayuit/tronggiay.png.png'),
(42, 19, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261561/shopbangiayuit/degiay.png.png'),
(43, 19, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261564/shopbangiayuit/magiay.png.png'),
(44, 19, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261567/shopbangiayuit/tronggiay.png.png'),
(45, 20, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261670/shopbangiayuit/degiay.png.png'),
(46, 20, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261673/shopbangiayuit/magiay.png.png'),
(47, 20, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261676/shopbangiayuit/tronggiay.png.png'),
(48, 21, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261739/shopbangiayuit/degiay.png.png'),
(49, 21, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261741/shopbangiayuit/magiay.png.png'),
(50, 21, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731261743/shopbangiayuit/tronggiay.png.png'),
(54, 22, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173943/shopbangiayuit/logo.jpg.jpg'),
(55, 22, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173945/shopbangiayuit/red%20%282%29.jpg.jpg'),
(56, 22, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731081634/shopbangiayuit/red.jpg.jpg'),
(57, 23, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173943/shopbangiayuit/logo.jpg.jpg'),
(58, 23, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173945/shopbangiayuit/red%20%282%29.jpg.jpg'),
(59, 23, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731081634/shopbangiayuit/red.jpg.jpg'),
(60, 18, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731076705/shopbangiayuit/blue.jpg.jpg'),
(61, 18, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173943/shopbangiayuit/logo.jpg.jpg'),
(62, 18, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173945/shopbangiayuit/red%20%282%29.jpg.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `shop`
--

CREATE TABLE `shop` (
  `shopId` int(11) NOT NULL,
  `logo` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `about` varchar(100) NOT NULL,
  `address` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(100) NOT NULL,
  `hotline` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `shop`
--

INSERT INTO `shop` (`shopId`, `logo`, `name`, `about`, `address`, `email`, `phone`, `hotline`) VALUES
(1, 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731173943/shopbangiayuit/logo.jpg.jpg', 'shop bán giày uit', 'day la shop bangiayuit', 'o ngoai duonggg', 'uit@gmail.com', '091234567890', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userId` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `avatar` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `birthday` varchar(100) DEFAULT NULL,
  `role` enum('Staff','Customer') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `username`, `password`, `name`, `avatar`, `phone`, `email`, `address`, `birthday`, `role`) VALUES
(32, 'huydepzai', '$2y$10$IuH0vQ8el5Q4zpR6Bmm5Ye7tyF3kAqdxrB5qcZOL3M1HardUkOYh.', 'Khát máu quáaaaaaaa', 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731001312/shopbangiayuit/blue%20main.png.png', '0359660269', 'huynickao123@gmail.com', 'nha e o ho da', '12/6/2003', 'Customer'),
(33, 'mingu', '$2y$10$skVtX7iiiFpBonp1YTvI7uRqF6g6hYydA43lcWp/rliZdeZybBPbO', 'Khát máu quá', 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731001312/shopbangiayuit/blue%20main.png.png', '0359660269', NULL, 'nha e o ho da', '12/5/2003', 'Customer'),
(34, 'huydepzaiii', '$2y$10$H8f9tsd50Dr0wr/06H4P/ucYYKgmrVe3Of68MJEUNusZzkFz738Iy', NULL, 'https://nupet.vn/wp-content/uploads/2023/10/anh-avatar-cute-meo-nupet-2.jpg', NULL, NULL, NULL, NULL, 'Customer'),
(36, 'minguu', '$2y$10$27kaNaO0dBbXuNEWuzmjVeJjBPMRR7NeT4MGHBhgRck8CsLHak112', 'Khát máu quáaaaaaaa', 'https://res.cloudinary.com/dxtslecpc/image/upload/v1731089441/shopbangiayuit/red%20%282%29.jpg.jpg', '0359660269', NULL, 'nha e o ho da', '12/6/2003', 'Customer'),
(37, 'minhquangu', '$2y$10$Mpybl4A6s4nTpwA5g.mq5.4Kfv/hEb/8f.UJ7FB8vgOQYXb83mJVa', NULL, 'https://nupet.vn/wp-content/uploads/2023/10/anh-avatar-cute-meo-nupet-2.jpg', NULL, NULL, NULL, NULL, 'Customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bannershop`
--
ALTER TABLE `bannershop`
  ADD PRIMARY KEY (`bannerId`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cartId`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`categoryId`);

--
-- Indexes for table `detailinvoice`
--
ALTER TABLE `detailinvoice`
  ADD PRIMARY KEY (`detailInvoiceId`);

--
-- Indexes for table `detailproduct`
--
ALTER TABLE `detailproduct`
  ADD PRIMARY KEY (`detailProductId`);

--
-- Indexes for table `forgetpassword`
--
ALTER TABLE `forgetpassword`
  ADD PRIMARY KEY (`forgetPasswordId`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoiceId`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`manufacturerId`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productId`);

--
-- Indexes for table `productotherimage`
--
ALTER TABLE `productotherimage`
  ADD PRIMARY KEY (`productOtherImageId`);

--
-- Indexes for table `shop`
--
ALTER TABLE `shop`
  ADD PRIMARY KEY (`shopId`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bannershop`
--
ALTER TABLE `bannershop`
  MODIFY `bannerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cartId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `detailinvoice`
--
ALTER TABLE `detailinvoice`
  MODIFY `detailInvoiceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `detailproduct`
--
ALTER TABLE `detailproduct`
  MODIFY `detailProductId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `forgetpassword`
--
ALTER TABLE `forgetpassword`
  MODIFY `forgetPasswordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoiceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `manufacturerId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `productotherimage`
--
ALTER TABLE `productotherimage`
  MODIFY `productOtherImageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `shop`
--
ALTER TABLE `shop`
  MODIFY `shopId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;