-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20221113.0eded7bb43
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 09 Gru 2022, 18:37
-- Wersja serwera: 10.4.24-MariaDB
-- Wersja PHP: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `schule_quizule`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `answer`
--

CREATE TABLE `answer` (
  `id` int(11) NOT NULL,
  `content` varchar(50) NOT NULL,
  `isCorrect` tinyint(4) NOT NULL,
  `questionId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `filled_answer`
--

CREATE TABLE `filled_answer` (
  `id` int(11) NOT NULL,
  `answerId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `category` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `isPublic` tinyint(4) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `owner_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `quiz_question`
--

CREATE TABLE `quiz_question` (
  `id` int(11) NOT NULL,
  `question` varchar(200) NOT NULL,
  `quizId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `teacherId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `room_quiz`
--

CREATE TABLE `room_quiz` (
  `id` int(11) NOT NULL,
  `quizId` int(11) NOT NULL,
  `roomId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `room_user`
--

CREATE TABLE `room_user` (
  `id` int(11) NOT NULL,
  `roomId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(30) NOT NULL,
  `surname` varchar(30) NOT NULL,
  `accountType` varchar(15) NOT NULL,
  `accountKey` char(36) NOT NULL,
  `verification_code` varchar(30) NOT NULL,
  `isVerificate` tinyint(4) NOT NULL,
  `passwordCode` varchar(16) NOT NULL,
  `passwordVerificate` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `name`, `surname`, `accountType`, `accountKey`, `verification_code`, `isVerificate`, `passwordCode`, `passwordVerificate`) VALUES
(1, 'a', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'c', 'd', 'e', 'f', '', 0, '', 0),
(2, 'adam@dobrzewkladam.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jakub', 'Styn', '1233', '334', '', 0, '', 0),
(3, 'adam@a.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'as', '1', '1', '', 0, '', 0),
(4, 'kamil@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jakub', 'Styn', '122', '3443', '', 0, '', 0),
(5, 'pol@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'Los', 'teacher', '2332', '', 0, '', 0),
(6, 'aga@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Lukasz', 'Trabka', 'user', '', '', 0, '', 0),
(7, 'kuba@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Nataniel', 'Kowalski', 'teacher', '31431', '', 0, '', 0),
(8, 'email@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Nataniel', 'Kowalski', 'teacher', '212323', '', 0, '', 0),
(9, 'k@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jakub', 'Wolski', 'teacher', '5c68394a-f420-4127-82a2-3d2eeb6e607c', '', 0, '', 0),
(10, 'piotr@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jan', 'Kowalski', 'user', '', '', 0, '', 0),
(11, 'zenon@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Kamil', 'Kamil', 'teacher', '432', '', 0, '', 0),
(12, 'w@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'ds', 'sd', 'user', '', '', 0, '', 0),
(13, 'as@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Lukasz', 'as', 'teacher', '4333', '', 0, '', 0),
(14, 'GH@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'HG', 'GH', 'teacher', '11', '', 0, '', 0),
(15, 'ffdg@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'fdsgfg', 'fdgfdg', 'teacher', '75665', '', 0, '', 0),
(16, 's2ad@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jan', 'as', 'teacher', 'cdd29137-d663-4cb2-8cb2-93a25ec6ab51', '', 0, '', 0),
(17, 'klol@p.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jakub', 'assa', 'user', '', '', 0, '', 0),
(18, 'sroka@pol.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Loka', 'Koka', 'user', '', '', 0, '', 0),
(19, 'rtyu@p2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Nataniel', 'nmasa', 'teacher', 'cdd29137-d663-4cb2-8cb2-93a25ec6ab51', '', 0, '', 0),
(20, 'zcx@xd.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Lukasz', 'xc', 'teacher', 'cdd29137-d663-4cb2-8cb2-93a25ec6ab51', '', 0, '', 0),
(21, 'natan@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Nataniel', 'Natanowski', 'teacher', 'dff11e15-601a-44e5-a43b-e24e6c5a4fa5', '', 0, '', 0),
(22, 'ispadam@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Adam', 'Wkładam', 'user', '', '', 0, '', 0),
(23, 'na@o2.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Nataniel', 'Aniel', 'user', '', '', 0, '', 0),
(24, 'popl@pl.pl', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'as', 'user', '', '', 0, '', 0),
(25, 'agnieszkae973@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Nataniel', 'Kowalski', 'user', '', '111347', 1, '', 0),
(26, 'polskiorzel19@gmail.com', '$2y$10$Ef.7SCce9CntikLpmcXm3uhUsTpNNy9KeFPxbr7QwbKGxLB/5Sspy', 'Piotr', 'Kozlarz', 'user', '', '287956', 1, '144589', 0),
(27, 'devtempmail1+edhgslvbnt@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Nataniel', 'Kowalski', 'user', '', '245798', 0, '', 0),
(28, 'devtempmail1+seyrnmwqyf@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Polkk', 'Polk', 'user', '', '140884', 0, '', 0),
(29, 'tyt@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Kamil', 'Kowalski', 'user', '', '132300', 0, '', 0),
(30, 'smieciem@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Polak', 'Polakowi', 'user', '', '165042', 0, '', 0),
(31, 'devtempmail1+wrgtjyrspv@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'Kukulski', 'user', '', '445218', 0, '', 0),
(32, 'pol@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Radek', 'S', 'user', '', '217947', 0, '', 0),
(33, 'trabka@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Lukasz', 'Kowal', 'user', '', '203864', 0, '', 0),
(34, 'maslo@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jakub', 'Gornik', 'user', '', '262922', 0, '', 0),
(35, 'trab@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'Trabka', 'user', '', '278864', 0, '', 0),
(36, 'fol@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jan', 'Styn', 'user', '', '167638', 0, '', 0),
(37, 'rtyu@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Bartek', 'Df', 'user', '', '893807', 0, '', 0),
(38, 'frt@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'df', 'fd', 'user', '', '682194', 0, '', 0),
(39, 'boku@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piko', 'Nopico', 'user', '', '112937', 0, '', 0),
(40, 'erty@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'Adam', 'user', '', '353828', 0, '', 0),
(41, 'nada@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Kamil', 'Kamilowski', 'user', '', '264563', 0, '', 0),
(42, 'polak@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Polak', 'Polakowski', 'user', '', '229917', 0, '', 0),
(43, 'qwe@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'fdg', 'df', 'user', '', '168846', 0, '', 0),
(44, 'rtgy@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Mateusz', 'Kalisz', 'user', '', '334940', 0, '', 0),
(45, 'uty@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'Trabka', 'user', '', '294783', 0, '', 0),
(46, 'tracz@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jakub', 'Stynm', 'user', '', '202916', 0, '', 0),
(47, 'tredzio@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'Treder', 'user', '', '109713', 0, '', 0),
(48, 'rower@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jan', 'Trabka', 'user', '', '295082', 0, '', 0),
(49, 'gm@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Mikolaj', 'Gietki', 'user', '', '229305', 0, '', 0),
(50, 'zxc@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Lukasz', 'Styn', 'user', '', '148939', 0, '', 0),
(51, 'fico@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Miko', 'Rico', 'user', '', '256738', 0, '', 0),
(52, 'skm@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Pioterek', 'Filterek', 'user', '', '243482', 0, '', 0),
(53, 'ok@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Olo', 'Kolo', 'user', '', '237952', 0, '', 0),
(54, 'wer@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Jan', 'Kowalski', 'user', '', '260919', 0, '', 0),
(55, 'fko@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Kamil', 'Flor', 'user', '', '312427', 0, '', 0),
(56, 'agawklada@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Aga', 'Ekman', 'user', '', '224074', 0, '', 0),
(57, 'beki74@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Beata', 'Styn', 'teacher', '313442ca-138c-4ea3-81ab-e2c52a049d60', '760383', 0, '', 0),
(58, 'mbudz@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Mateusz', 'Budzynski', 'user', '', '459638', 0, '', 0),
(59, 'ma@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Mateusz', 'Budzynski', 'user', '', '480082', 0, '', 0),
(60, 'ptr@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Piotr', 'Trabka', 'user', '', '152307', 0, '', 0),
(61, 'psrt@gmail.com', '$2y$10$W52bW4ujP3Wb8MpFqzAxtOY5nSQ.40HibxnFvEIGbADKYslBjbSty', 'Polsat', 'Sport', 'user', '', '243977', 0, '', 0),
(62, 'lospo@gmail.com', '$2y$10$SJCt0FI6XGCno7Ba2HqRiOs9dGqjYf6P9C.I29g35sJZF5540cCkC', 'Piotr', 'Los', 'user', '', '279787', 0, '489540', 0),
(63, 'kkowal@gmail.com', '$2y$10$IMXn35.ZVfCFSVNJh7dUMODt0bL8XJeLH6BfvDNfqSPtGHBbZtM2a', 'Kamil', 'Kowalski', 'user', '', '264155', 0, '215035', 0),
(64, 'pk@gmail.com', '$2y$10$2WIKX5gu0udNAitB8hGBR.5fbHSDonwM0ADufwfDaoDu6hOc3KGIC', 'Piotr', 'Kaminski', 'user', '', '914157', 0, '0', 0),
(65, 'pp@gmail.com', '$2y$10$BZI4R.lN90wh0oaNQSuMNul3WfbkvcNQKDj3pYjzQ9jPi1GGrsmG2', 'Piotr', 'P[olska', 'user', '', '153117', 1, '215574', 1),
(66, 'kpl@gmail.com', '$2y$10$84X1FMsSv8yAZFNz5VYonOiT5L8tUqI.0.uMRqrfFybZCzd4AqBY6', 'Kuba', 'Polski', 'user', '', '354578', 1, '0', 0),
(67, 'pkowal@gmail.com', '$2y$10$rPuYjnNHJ1SYxZWxXLtswe9ahsCbMyqxnKaupXqcV5vOEoHtk5dEm', 'Piotrek', 'Kowalski', 'user', '', '572394', 1, '342048', 1),
(68, 'paczek@gmail.com', '$2y$10$aBmMlK2U4hSJNldAxIC7.OPUS9yfTwQ06DfEdfAyHdMdefIvTFe2S', 'Jakub', 'Styn', 'user', '', '675008', 1, '0', 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user_quiz`
--

CREATE TABLE `user_quiz` (
  `id` int(11) NOT NULL,
  `quizId` int(11) NOT NULL,
  `userId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `answer`
--
ALTER TABLE `answer`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `filled_answer`
--
ALTER TABLE `filled_answer`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `quiz_question`
--
ALTER TABLE `quiz_question`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `room_quiz`
--
ALTER TABLE `room_quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `room_user`
--
ALTER TABLE `room_user`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `user_quiz`
--
ALTER TABLE `user_quiz`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `answer`
--
ALTER TABLE `answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `filled_answer`
--
ALTER TABLE `filled_answer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `quiz_question`
--
ALTER TABLE `quiz_question`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `room_quiz`
--
ALTER TABLE `room_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `room_user`
--
ALTER TABLE `room_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT dla tabeli `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT dla tabeli `user_quiz`
--
ALTER TABLE `user_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
