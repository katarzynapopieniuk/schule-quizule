-- phpMyAdmin SQL Dump
-- version 5.3.0-dev+20221113.0eded7bb43
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 24 Lis 2022, 00:18
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
  `isVerificate` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `name`, `surname`, `accountType`, `accountKey`, `verification_code`, `isVerificate`) VALUES
(1, 'a', 'b', 'c', 'd', 'e', 'f', '', '0'),
(2, 'adam@dobrzewkladam.com', '$2y$10$XDOhZWpxkkcug9YIu10tKeg', 'Jakub', 'Styn', '1233', '334', '', '0'),
(3, 'adam@a.com', '$2y$10$AnN4y4.v12kwBH2Ck.u4uew', 'Piotr', 'as', '1', '1', '', '0'),
(4, 'kamil@o2.pl', '$2y$10$dhm3dKVhKwt.giayNjLWAeK', 'Jakub', 'Styn', '122', '3443', '', '0'),
(5, 'pol@o2.pl', '$2y$10$eBr6c6EjSsCf8xqip7dx7u/', 'Piotr', 'Los', 'teacher', '2332', '', '0'),
(6, 'aga@o2.pl', '$2y$10$dzUxyNIbgQtfJfFoE2Gv/.a', 'Lukasz', 'Trabka', 'user', '', '', '0'),
(7, 'kuba@o2.pl', '$2y$10$CdfDn6QbdihE/4UerYZPCuj', 'Nataniel', 'Kowalski', 'teacher', '31431', '', '0'),
(8, 'email@o2.pl', '$2y$10$POwROU4W1bWBFe5CQtlqK.v', 'Nataniel', 'Kowalski', 'teacher', '212323', '', '0'),
(9, 'k@o2.pl', 'Apocohaslo1', 'Jakub', 'Wolski', 'teacher', '5c68394a-f420-4127-82a2-3d2eeb6e607c', '', '0'),
(10, 'piotr@o2.pl', '$2y$10$6S7Iwa4V3ehj9PC30mCEGuE', 'Jan', 'Kowalski', 'user', '', '', '0'),
(11, 'zenon@o2.pl', '$2y$10$4B7IM2w4gC9RFFyJ2jBLTum', 'Kamil', 'Kamil', 'teacher', '432', '', '0'),
(12, 'w@o2.pl', '$2y$10$1fHTF6nRuoqzYSXiuHp.nuF', 'ds', 'sd', 'user', '', '', '0'),
(13, 'as@o2.pl', '$2y$10$3XnLa3ZRb1yi.j/3V8Mkg.C', 'Lukasz', 'as', 'teacher', '4333', '', '0'),
(14, 'GH@o2.pl', '$2y$10$7ekkg2X7uzT1SsEdtw8Nduy', 'HG', 'GH', 'teacher', '11', '', '0'),
(15, 'ffdg@o2.pl', '$2y$10$9lmXdj/HQtCZH96dSi2mROk', 'fdsgfg', 'fdgfdg', 'teacher', '75665', '', '0'),
(16, 's2ad@o2.pl', '$2y$10$W0PIP7YYBc1KDs7lefpnJOw', 'Jan', 'as', 'teacher', 'cdd29137-d663-4cb2-8cb2-93a25ec6ab51', '', '0'),
(17, 'klol@p.pl', '$2y$10$F6CRLKnLipn4jiGX00ODvOQ', 'Jakub', 'assa', 'user', '', '', '0'),
(18, 'sroka@pol.pl', '$2y$10$FjeYTHe1VFy9qwAEExDRZec', 'Loka', 'Koka', 'user', '', '', '0'),
(19, 'rtyu@p2.pl', '$2y$10$QfeDAMOAchErmjdOOTcar.9', 'Nataniel', 'nmasa', 'teacher', 'cdd29137-d663-4cb2-8cb2-93a25ec6ab51', '', '0'),
(20, 'zcx@xd.pl', '$2y$10$vBlzH6/3YXz95HAPdb4GYuo', 'Lukasz', 'xc', 'teacher', 'cdd29137-d663-4cb2-8cb2-93a25ec6ab51', '', '0'),
(21, 'natan@o2.pl', '$2y$10$co3s.hzza5A2CpCg5/oPIug', 'Nataniel', 'Natanowski', 'teacher', 'dff11e15-601a-44e5-a43b-e24e6c5a4fa5', '', '0'),
(22, 'ispadam@o2.pl', '$2y$10$s6eZPIr7QetKuCwrmuEdoORL9VGiOs8T/JwqFfW5eQEpwpFF.vOeW', 'Adam', 'Wkładam', 'user', '', '', '0'),
(23, 'na@o2.pl', '$2y$10$o9fYxwRFEJhMBZOOiKNGgeTgPbnhKxIXi9ILdndUYpV19RMAICtsO', 'Nataniel', 'Aniel', 'user', '', '', '0'),
(24, 'popl@pl.pl', '$2y$10$OeWvyGnc8M8ktU1quIQzJutXsNoU315s/jGrXiQz9iiQbsKRNHpV6', 'Piotr', 'as', 'user', '', '', '0'),
(25, 'agnieszkae973@gmail.com', '$2y$10$0CQt4lhkCHi4G7u72hDSq.QbLz74Lij6pWISwEmnTJwBQvt0kokhS', 'Nataniel', 'Kowalski', 'user', '', '111347', '1'),
(26, 'polskiorzel19@gmail.com', '$2y$10$eAzypjWmMh/K0C7ttKvMjOha3wUM7cOnmyh5aTbA94z4tQc41bROa', 'Piotr', 'Kozlarz', 'user', '', '287956', '1'),
(27, 'devtempmail1+edhgslvbnt@gmail.com', '$2y$10$wn2fvBzYiZb4SCQpeq6wq.B8WyH3k8YZH8t.UCUC9c/R/x730nZ8O', 'Nataniel', 'Kowalski', 'user', '', '245798', '0'),
(28, 'devtempmail1+seyrnmwqyf@gmail.com', '$2y$10$SMsES9/7wU2MxcgTxAsst.d0y2Dh8363p40fp.GQdEybq.SoCFeUS', 'Polkk', 'Polk', 'user', '', '140884', '0'),
(29, 'tyt@gmail.com', '$2y$10$Gtf9zMqFromkPYCApLAZeuwBc5OdZEux1MyGQgfIrAq0HFDk8jX3W', 'Kamil', 'Kowalski', 'user', '', '132300', '0'),
(30, 'smieciem@gmail.com', '$2y$10$otE0YyHpmBIpD9UOiG4S3uvzZ/8.1fI8qVezq6do.GRkp5ynU/Ub2', 'Polak', 'Polakowi', 'user', '', '165042', '0'),
(31, 'devtempmail1+wrgtjyrspv@gmail.com', '$2y$10$m7WwKBKDIKt1rwktstoXSu4NL35k/dERGfQ0ZiIgOF6UCFjgu2keS', 'Piotr', 'Kukulski', 'user', '', '445218', '0'),
(32, 'pol@gmail.com', '$2y$10$gIzHmzfHnIjEXG3PrBF9De45eazWwCys.igSr9hjiS379PkZZrM6m', 'Radek', 'S', 'user', '', '217947', '0'),
(33, 'trabka@gmail.com', '$2y$10$u.U7to1Dd5P9sSUtyQaemuhkZMiWMd3k8ZCzp3Qpe8j8bgfykXz6i', 'Lukasz', 'Kowal', 'user', '', '203864', '0'),
(34, 'maslo@gmail.com', '$2y$10$uxvr.we/3MrWlvIm.puepezqwutFFZL861VV/npgR/YBeVDEE0F3G', 'Jakub', 'Gornik', 'user', '', '262922', '0'),
(35, 'trab@gmail.com', '$2y$10$S37T5ia3L3xX0FVyzydhdeydmpmp/FNOPG9b1WZ9pUW9kPBF4J.YW', 'Piotr', 'Trabka', 'user', '', '278864', '0'),
(36, 'fol@gmail.com', '$2y$10$IhJJj54JXFjf2B24zWozheFiq581qMv5oQHQnOr.wEm.bz.YAay0W', 'Jan', 'Styn', 'user', '', '167638', '0'),
(37, 'rtyu@gmail.com', '$2y$10$sU.VP9n/q8cA004PASWSeOrMjMknxxq0X6PRmGbJJ3OnUR4RtyvGK', 'Bartek', 'Df', 'user', '', '893807', '0'),
(38, 'frt@gmail.com', '$2y$10$O4Kjz6rjCPrcTWtuJ.bdK.sn5XNYcpJcNYZ8Q0all3xb9fNnwe9pm', 'df', 'fd', 'user', '', '682194', '0'),
(39, 'boku@gmail.com', '$2y$10$xKMHZeaZG35f9Et7buXAfuM8sn1a2pRKTusUwwr7ZOrVvQdQLlR7O', 'Piko', 'Nopico', 'user', '', '112937', 'false'),
(40, 'erty@gmail.com', '$2y$10$wFKhIMv4HzCk45awS3cVMe6g3O3AvQ1h0E125UNc4AV.QgG529e.y', 'Piotr', 'Adam', 'user', '', '353828', 'true'),
(41, 'nada@gmail.com', '$2y$10$2weyRzmMqnVgKZdGJF5jZOfBr1ds8VYaKMsmP2koYWhSBDUwhiDh2', 'Kamil', 'Kamilowski', 'user', '', '264563', 'true'),
(42, 'polak@gmail.com', '$2y$10$b2zA7tVjT4EujgouQBwPnOP.GpsShcwJSk6Z.hdqgOMxA2Mdl7ZT2', 'Polak', 'Polakowski', 'user', '', '229917', 'false'),
(43, 'qwe@gmail.com', '$2y$10$cB9tgY3R0IiRvTakqqPTeO9BU7Huwlw1x5W8g5SED52RSKdR03wIy', 'fdg', 'df', 'user', '', '168846', 'false'),
(44, 'rtgy@gmail.com', '$2y$10$Ao2TxrrHQc4qNFAaxUkos.pXrZkUx89K7o8CMUCNOt.px9.ZTeikO', 'Mateusz', 'Kalisz', 'user', '', '334940', 'true'),
(45, 'uty@gmail.com', 'Apocohaslo1', 'Piotr', 'Trabka', 'user', '', '294783', 'false'),
(46, 'tracz@gmail.com', 'Apocohaslo1', 'Jakub', 'Stynm', 'user', '', '202916', 'false'),
(47, 'tredzio@gmail.com', '$2y$10$1aS30vu15XNXWHsVVbdVMeLqKiEdHWYfS0JQ.yb4eLLgX7OW/CB3G', 'Piotr', 'Treder', 'user', '', '109713', 'false'),
(48, 'rower@gmail.com', '$2y$10$kgtZzRY3CvT9lK3ytmQTxOOTrisa7G9mnXhExRSR5pwn8EJKt5oJ6', 'Jan', 'Trabka', 'user', '', '295082', 'false'),
(49, 'gm@gmail.com', '$2y$10$I3LHHWw37zw9v6134/MNIO/tpqiuHjIPY7/lmpHbsKHsgRMokEn4.', 'Mikolaj', 'Gietki', 'user', '', '229305', 'false'),
(50, 'zxc@gmail.com', '$2y$10$qRVta8qFVDV6wi8XLMqF8OZr5Zs1DUf7D8qrhX.v6clpc9mXHvHfG', 'Lukasz', 'Styn', 'user', '', '148939', 'true'),
(51, 'fico@gmail.com', '$2y$10$i9Vpr1GvX.NMOgVXSEtkjOL9TTVP6RtdcN6/BMCWTpZl7FLJuocPa', 'Miko', 'Rico', 'user', '', '256738', 'false'),
(52, 'skm@gmail.com', '$2y$10$zL4c23X.jXZKIe4UWW5mXuzU9H.DGmhiy.yfTNzo7VTEjZvhj8wFG', 'Pioterek', 'Filterek', 'user', '', '243482', 'true'),
(53, 'ok@gmail.com', '$2y$10$lsKBv11WqzUR0dw7BYVk/umFEmt45PmhGXAxH3mn16dYxfoMgD/ha', 'Olo', 'Kolo', 'user', '', '237952', 'false'),
(54, 'wer@gmail.com', '$2y$10$TaDk8TEzBU.gPYv5as1vnerZxF.svYBMCG5suCbtlI9okkvPP/U8C', 'Jan', 'Kowalski', 'user', '', '260919', 'false'),
(55, 'fko@gmail.com', '$2y$10$zv9WfoLiG9lnnBVtVJD0muR6cD7jwIkLxy4WQEyDP9Dro6k9NKAwO', 'Kamil', 'Flor', 'user', '', '312427', 'true'),
(56, 'agawklada@gmail.com', '$2y$10$Cs5R69liA/68ouTnLAU3yeNSgjRJGeWGYQYAKjbKiboJR2ZfAQiEy', 'Aga', 'Ekman', 'user', '', '224074', 'true'),
(57, 'beki74@gmail.com', '$2y$10$c.WCnKGphl2xc6cXq2KA/e1JXcNw51InklXa94gaGl620OuxddevS', 'Beata', 'Styn', 'teacher', '313442ca-138c-4ea3-81ab-e2c52a049d60', '760383', 'true'),
(58, 'mbudz@gmail.com', '$2y$10$E5eYwEJhjVoiS4i2Iaz1lOkIzrzCXDPXjs9Lk6Z/DWsl5XYNCh0cG', 'Mateusz', 'Budzynski', 'user', '', '459638', 'true'),
(59, 'ma@gmail.com', '$2y$10$wokZt9AOT9GtLq6E6jCml..RdzJxOJffWg.648.An9ujdozdxyY8u', 'Mateusz', 'Budzynski', 'user', '', '480082', 'true'),
(60, 'ptr@gmail.com', '$2y$10$QM5g8dBLLRlw6k3v78W36um8yXiqclp.TRmPznCpi9e7.eNCabq/K', 'Piotr', 'Trabka', 'user', '', '152307', 'true'),
(61, 'psrt@gmail.com', '$2y$10$WwfOBNYA7I46hk6huDfuFOqHpMWcRtYpxS2EJCFJOHHzNcFUiC2qO', 'Polsat', 'Sport', 'user', '', '243977', 'true');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT dla tabeli `user_quiz`
--
ALTER TABLE `user_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
