-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- ホスト: localhost
-- 生成日時: 2022 年 1 月 28 日 13:23
-- サーバのバージョン： 10.4.21-MariaDB
-- PHP のバージョン: 8.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `diy_sns`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `categories_table`
--

CREATE TABLE `categories_table` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_bin NOT NULL,
  `slug` varchar(128) COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- テーブルのデータのダンプ `categories_table`
--

INSERT INTO `categories_table` (`id`, `name`, `slug`) VALUES
(1, '戸建て', 'detached_house'),
(2, 'マンション', 'apartment'),
(3, '古民家', 'old_house'),
(4, 'リビング', 'living'),
(5, '寝室', 'bedroom'),
(6, 'キッチン', 'kitchen'),
(7, '浴室', 'bathroom'),
(8, 'トイレ', 'toiletroom'),
(9, '玄関', 'entrance'),
(10, '外観', 'exterior'),
(11, 'ガーデン', 'garden'),
(12, 'その他', 'other');

-- --------------------------------------------------------

--
-- テーブルの構造 `posts_table`
--

CREATE TABLE `posts_table` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `image_before` varchar(128) COLLATE utf8mb4_bin DEFAULT NULL,
  `image_after` varchar(128) COLLATE utf8mb4_bin DEFAULT NULL,
  `title` varchar(128) COLLATE utf8mb4_bin NOT NULL,
  `body` text COLLATE utf8mb4_bin NOT NULL,
  `is_deleted` int(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- テーブルのデータのダンプ `posts_table`
--

INSERT INTO `posts_table` (`id`, `user_id`, `image_before`, `image_after`, `title`, `body`, `is_deleted`, `created_at`, `updated_at`) VALUES
(33, 1, '/upload/202201270852100110ea9e7aecbba6a7ffb998807d51a2357ab0c0949b206346b59c61172c48a3.jpg', '/upload/202201270852100110ea9e7aecbba6a7ffb998807d51a295f459fa718a6c4a2ca5970f4e4e53f9.jpg', 'リビングをアンティーク風にリノベーションしました', 'うふふ', 0, '2022-01-27 16:52:10', '2022-01-27 16:52:10'),
(34, 1, '/upload/2022012708591436a6c9e15d1c0c997a49bfac14e5199f6d811d858d310e8bb0cf8f869bbecd79.jpg', '/upload/2022012708591436a6c9e15d1c0c997a49bfac14e5199f26649800d77c6112539e233ae557999c.jpg', 'リメイクシートを貼るだけでいい感じになりました', 'すごいじゃろすごいじゃろすごいじゃろすごいじゃろすごいじゃろすごいじゃろすごいじゃろ', 0, '2022-01-27 16:59:14', '2022-01-27 16:59:14'),
(35, 2, '/upload/2022012809453961e3b8cbe67b7b379875d875c214045ef16b2bcfe5fa45e3e13e2d4b632ba6c4.jpg', '/upload/2022012809453961e3b8cbe67b7b379875d875c214045e71092ede6279312de57885243c726706.jpg', '賃貸マンションのトイレにはがせるクロスを貼ってみました', 'すごじゃろ〜', 0, '2022-01-28 17:45:39', '2022-01-28 17:45:39'),
(36, 2, '/upload/2022012809471048fe32af132721e83a948163d7b8df24554d022636175ca2ecd0c817b7448821.jpg', '/upload/2022012809471048fe32af132721e83a948163d7b8df24efec278cffc33084b08d052d210291d6.jpg', 'モルテックスで仕上げた玄関', 'うふふ', 0, '2022-01-28 17:47:10', '2022-01-28 17:47:10');

-- --------------------------------------------------------

--
-- テーブルの構造 `post_category`
--

CREATE TABLE `post_category` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- テーブルのデータのダンプ `post_category`
--

INSERT INTO `post_category` (`id`, `post_id`, `category_id`) VALUES
(33, 33, 1),
(34, 33, 4),
(35, 34, 3),
(36, 34, 6),
(37, 35, 2),
(38, 35, 8),
(39, 36, 3),
(40, 36, 9);

-- --------------------------------------------------------

--
-- テーブルの構造 `users_table`
--

CREATE TABLE `users_table` (
  `id` int(11) NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  `password` varchar(128) COLLATE utf8mb4_bin NOT NULL,
  `image_profile` varchar(128) COLLATE utf8mb4_bin DEFAULT NULL,
  `description` text COLLATE utf8mb4_bin DEFAULT NULL,
  `is_admin` int(1) NOT NULL,
  `is_deleted` int(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- テーブルのデータのダンプ `users_table`
--

INSERT INTO `users_table` (`id`, `name`, `email`, `password`, `image_profile`, `description`, `is_admin`, `is_deleted`, `created_at`, `updated_at`) VALUES
(1, 'IKURA', 'diyer_a@example.com', '$2y$10$IptMLf/UWAXN.UCl.uBdkeF6RQmjP46PZnu3FG5mrYWZuZsobL1jC', '/upload/20220128124405399440d0ec869e9bb601138ad24e7add082750e14cdde23fec6e7be3d1d96903.jpeg', 'UNIT_YAMAGUCHI公式サンプルユーザーのIKURAです！', 0, 0, '2022-01-20 22:13:11', '2022-01-28 20:44:05'),
(2, 'B', 'diyer_b@example.com', '$2y$10$kuAXfbt0w6RERuQL8FMGtu5wqI5GojoD3KgNH0/weG4vAAwpW07HO', NULL, '', 0, 0, '2022-01-20 22:15:02', '2022-01-20 22:15:02'),
(3, 'C', 'diyer_c@example.com', '$2y$10$AuJwclUJH3MlgF.mYvyrVeg0oprz5wIFBbw.U5j2nSS4UWnqNzNki', NULL, '', 0, 0, '2022-01-20 22:15:42', '2022-01-20 22:15:42'),
(4, 'D', 'diyer_d@example.com', '$2y$10$e6rgW60/cawElzhAHLJoceiQdGw4gVqdzobU8szByiVKlQMUJymkG', NULL, '', 0, 0, '2022-01-20 22:43:09', '2022-01-20 22:43:09'),
(5, 'E', 'diyer_e@example.com', '$2y$10$aN9Nb6EzSpj0g3.tuGRw5e41/P8yDps.fT5C4zluXZyOf8/hKwLRm', NULL, '', 0, 0, '2022-01-20 22:50:36', '2022-01-20 22:50:36');

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `categories_table`
--
ALTER TABLE `categories_table`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `posts_table`
--
ALTER TABLE `posts_table`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `post_category`
--
ALTER TABLE `post_category`
  ADD PRIMARY KEY (`id`);

--
-- テーブルのインデックス `users_table`
--
ALTER TABLE `users_table`
  ADD PRIMARY KEY (`id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `categories_table`
--
ALTER TABLE `categories_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- テーブルの AUTO_INCREMENT `posts_table`
--
ALTER TABLE `posts_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- テーブルの AUTO_INCREMENT `post_category`
--
ALTER TABLE `post_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- テーブルの AUTO_INCREMENT `users_table`
--
ALTER TABLE `users_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
