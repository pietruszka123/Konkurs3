-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 21 Paź 2021, 19:37
-- Wersja serwera: 10.4.14-MariaDB
-- Wersja PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `dziennik`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `attendance`
--

CREATE TABLE `attendance` (
  `attendanceId` int(8) NOT NULL,
  `studentId` int(8) NOT NULL,
  `teacherId` int(8) NOT NULL,
  `subjectId` int(8) NOT NULL,
  `subjectNumber` int(2) NOT NULL,
  `attendanceState` varchar(255) NOT NULL,
  `attendanceDescription` text DEFAULT NULL,
  `attendanceDate` datetime NOT NULL DEFAULT current_timestamp(),
  `attendanceExcuse` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `attendance`
--

INSERT INTO `attendance` (`attendanceId`, `studentId`, `teacherId`, `subjectId`, `subjectNumber`, `attendanceState`, `attendanceDescription`, `attendanceDate`, `attendanceExcuse`) VALUES
(1, 3, 1, 1, 1, 'Obecnosc', NULL, '2021-07-19 08:49:53', NULL),
(2, 3, 1, 5, 3, 'Obecnosc', NULL, '2021-10-16 02:09:04', NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `classes`
--

CREATE TABLE `classes` (
  `classId` int(8) NOT NULL,
  `classStartYear` year(4) NOT NULL,
  `classGrade` int(1) NOT NULL,
  `classLetter` char(1) DEFAULT NULL,
  `classProfile` varchar(255) NOT NULL,
  `classType` varchar(255) NOT NULL,
  `mentorId` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `classes`
--

INSERT INTO `classes` (`classId`, `classStartYear`, `classGrade`, `classLetter`, `classProfile`, `classType`, `mentorId`) VALUES
(1, 2018, 2, 'a', 'Szambooczyszczacz', 'Technikum', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE `comments` (
  `commentId` int(8) NOT NULL,
  `commentType` varchar(255) NOT NULL,
  `commentWeight` int(8) NOT NULL,
  `commentContent` text NOT NULL,
  `commentDate` datetime NOT NULL DEFAULT current_timestamp(),
  `teacherId` int(8) NOT NULL,
  `studentId` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`commentId`, `commentType`, `commentWeight`, `commentContent`, `commentDate`, `teacherId`, `studentId`) VALUES
(1, 'Uwaga Pozytywna', 6, 'Tworzył metamfetaminę na teoretycznych zajęciach z Teoretycznej Metaamfetaminie Kwantowej.', '2021-07-19 08:40:00', 1, 3),
(2, 'Uwaga Pozytywna', 15, 'Zesrał sie na stole dyrektora podczas obiadu', '2021-10-15 14:45:44', 1, 3),
(3, 'Uwaga Pozytywna', 99, 'Wpierdolił nauczycielce', '2021-10-15 16:56:32', 7, 3),
(4, 'Uwaga Negatywna', 23, 'Uczeń zesrał się do torebki pani od polskiego', '2021-10-15 16:56:32', 8, 3);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `exams`
--

CREATE TABLE `exams` (
  `examId` int(8) NOT NULL,
  `examDate` date NOT NULL,
  `subjectId` int(8) NOT NULL,
  `teacherId` int(8) NOT NULL,
  `examDescription` text NOT NULL,
  `examType` varchar(255) NOT NULL,
  `classId` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `exams`
--

INSERT INTO `exams` (`examId`, `examDate`, `subjectId`, `teacherId`, `examDescription`, `examType`, `classId`) VALUES
(1, '2021-07-20', 1, 1, 'Sprawdzian oralny', 'Oralny', 1),
(2, '2021-10-25', 5, 7, 'Examin z gotowanie wodki', 'Pisemny', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `freedays`
--

CREATE TABLE `freedays` (
  `freeDayId` int(11) NOT NULL,
  `freeDayDate` date NOT NULL,
  `freeDayReason` varchar(255) NOT NULL,
  `freeDayDescription` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `freedays`
--

INSERT INTO `freedays` (`freeDayId`, `freeDayDate`, `freeDayReason`, `freeDayDescription`) VALUES
(1, '2021-10-18', 'SPIERDALAJ', 'WYPIERDALANKO DO DOMSKU'),
(2, '2021-11-26', 'bo jestem chory byczku', 'no choroba wyszloc o mam poradzic szczylu maly'),
(3, '2021-10-31', 'bo tak', 'bo mi sie nie chce przychodzi mlody');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `gradecolumns`
--

CREATE TABLE `gradecolumns` (
  `columnId` int(11) NOT NULL,
  `gradeWeight` int(3) NOT NULL,
  `gradeDescription` varchar(255) NOT NULL,
  `classId` int(11) NOT NULL,
  `columnPosition` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `gradecolumns`
--

INSERT INTO `gradecolumns` (`columnId`, `gradeWeight`, `gradeDescription`, `classId`, `columnPosition`) VALUES
(1, 2, 'Kartkówka z trójkąta', 1, 1),
(2, 3, 'Pytanie z kwadratu', 1, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `grades`
--

CREATE TABLE `grades` (
  `gradeId` int(8) NOT NULL,
  `studentId` int(8) NOT NULL,
  `gradeScale` int(1) NOT NULL,
  `gradeWeight` int(3) NOT NULL,
  `teacherId` int(8) NOT NULL,
  `gradeDescription` text NOT NULL,
  `subjectId` int(8) NOT NULL,
  `gradeDate` datetime NOT NULL DEFAULT current_timestamp(),
  `classId` int(8) NOT NULL,
  `columnId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `grades`
--

INSERT INTO `grades` (`gradeId`, `studentId`, `gradeScale`, `gradeWeight`, `teacherId`, `gradeDescription`, `subjectId`, `gradeDate`, `classId`, `columnId`) VALUES
(1, 3, 6, 420, 1, 'Zdał sprawdzian oralny', 1, '2021-07-19 08:44:08', 1, 0),
(2, 3, 4, 1, 4, 'Sprawdzian z mnożenia.', 2, '2021-07-20 09:55:50', 1, 0),
(3, 11, 3, 1, 7, 'Sprawdzian z robienia lodów', 5, '2021-07-21 11:35:35', 1, 0),
(4, 11, 4, 2, 8, 'Sprawdzian z globusa Polski', 7, '2021-07-21 11:36:23', 1, 0),
(12, 3, 2, 1, 7, 'Dwójeczka na prośbę mojego ulubionego ucznia, ponieważ jest pierwszy.', 4, '2021-07-22 14:07:45', 1, 0),
(13, 3, 6, 2, 1, 'Bardzo dobrze zapoznał się z procesem tworzenia metamfetaminy.', 1, '2021-07-22 14:07:45', 1, 0),
(14, 3, 6, 6, 1, 'No i kolejna szósteczka', 1, '2021-07-23 08:58:16', 1, 0),
(15, 3, 3, 1, 7, 'Za tą zimną kawę', 4, '2021-07-23 08:58:16', 1, 0),
(16, 3, 6, 2, 1, 'Najlepszy uczeń na świecie', 1, '2021-07-23 08:59:41', 1, 0),
(17, 3, 5, 1, 1, 'Panie Pawle, co się stało? 5 a nie 6?', 1, '2021-07-23 08:59:41', 1, 0),
(18, 3, 1, 3, 8, 'Nie wie gdzie jest Polsza', 7, '2021-07-23 09:01:23', 1, 0),
(19, 3, 4, 2, 8, 'Ummm fizics', 6, '2021-07-23 09:01:23', 1, 0),
(20, 3, 6, 1, 1, 'No i teraz lepiej Panie Pawle', 1, '2021-07-23 09:02:45', 1, 0),
(21, 3, 3, 1, 1, 'Nie znamy się więcej Panie X', 1, '2021-07-23 09:02:45', 1, 0),
(22, 3, 4, 2, 4, 'Kartkówka z trójkąta', 2, '2021-10-21 19:14:11', 1, 1),
(23, 3, 5, 3, 4, 'Pytanie z kwadratu', 2, '2021-10-21 19:14:11', 1, 2);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `homework`
--

CREATE TABLE `homework` (
  `homeworkId` int(8) NOT NULL,
  `subjectId` int(8) NOT NULL,
  `teacherId` int(8) NOT NULL,
  `creationDate` datetime NOT NULL DEFAULT current_timestamp(),
  `deadline` datetime DEFAULT NULL,
  `homeworkDescription` text NOT NULL,
  `obligatory` varchar(255) NOT NULL,
  `classId` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `homework`
--

INSERT INTO `homework` (`homeworkId`, `subjectId`, `teacherId`, `creationDate`, `deadline`, `homeworkDescription`, `obligatory`, `classId`) VALUES
(1, 1, 1, '2021-07-19 08:46:28', '2021-07-20 06:45:39', 'Przygotuj czystą metamfetaminę', 'Tak', 1),
(2, 6, 1, '2021-10-17 19:53:59', '2021-10-28 19:53:20', 'dfsgsdfgsdfg', 'Tak', 1),
(3, 2, 4, '2021-10-17 19:53:59', '2021-10-26 19:53:20', 'asdfasdfasdfasdf', 'Nie', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `luckynumbers`
--

CREATE TABLE `luckynumbers` (
  `databaseDate` date NOT NULL,
  `luckyNumberFirst` int(11) NOT NULL,
  `luckyNumberSecond` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `luckynumbers`
--

INSERT INTO `luckynumbers` (`databaseDate`, `luckyNumberFirst`, `luckyNumberSecond`) VALUES
('2021-10-13', 15, 26),
('2021-10-14', 15, 19),
('2021-10-21', 15, 18);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `meetings`
--

CREATE TABLE `meetings` (
  `meetingId` int(8) NOT NULL,
  `teacherId` int(8) NOT NULL,
  `meetingDescription` text NOT NULL,
  `meetingDate` datetime NOT NULL,
  `meetingForm` varchar(255) NOT NULL,
  `meetingClassroom` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `meetings`
--

INSERT INTO `meetings` (`meetingId`, `teacherId`, `meetingDescription`, `meetingDate`, `meetingForm`, `meetingClassroom`) VALUES
(1, 1, 'Spotykamy się, aby obmówić oralnie ważną sytuację ekonomii Polskiej metaamfetaminy', '2021-07-19 06:46:38', 'Irl', '694201337213769');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `mentors`
--

CREATE TABLE `mentors` (
  `mentorId` int(8) NOT NULL,
  `teacherId` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `mentors`
--

INSERT INTO `mentors` (`mentorId`, `teacherId`) VALUES
(1, 1),
(2, 4);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `messages`
--

CREATE TABLE `messages` (
  `messageId` int(8) NOT NULL,
  `senderId` int(8) NOT NULL,
  `receiverId` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `messageContent` text NOT NULL,
  `messageDate` datetime NOT NULL DEFAULT current_timestamp(),
  `messageTitle` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `messages`
--

INSERT INTO `messages` (`messageId`, `senderId`, `receiverId`, `messageContent`, `messageDate`, `messageTitle`) VALUES
(1, 1, '{\r\n\"id\": [\"3\"]\r\n}', 'Elo kurwiu, jutro poniedziałek, wpadaj do budy', '2021-07-19 08:42:54', 'WRACASZ KURWA!'),
(2, 7, '{\r\n\"rank\": [\"3\"]\r\n}', 'Jutro masz do mnie przyjść na specjalną ceremonię odznaczenia. Zostaniesz odznaczony, za swoją służbę jako największy dzban klasy.', '2021-07-27 13:54:39', 'Uroczystość'),
(3, 3, '{\r\n\"id\": [\"11\"]\r\n}', 'Dzban', '2021-07-29 09:09:36', 'Dzbaniura'),
(17, 3, '{ \"id\": [1]}', 'asdfasdf', '2021-07-29 09:50:31', 'asdfasdf'),
(18, 3, '{ \"id\": [1]}', 'test', '2021-07-29 09:50:37', 'test'),
(19, 3, '{ \"id\": [1]}', 'test', '2021-07-29 10:51:01', 'test'),
(20, 3, '{ \"id\": [1]}', 'wybierz auto ', '2021-09-02 12:35:41', 'Elo'),
(21, 3, '{ \"id\": [3]}', 'jestes chuj', '2021-09-09 10:41:05', 'chuj'),
(22, 3, '{ \"id\": [3]}', 'jestes chuj', '2021-09-09 10:41:09', 'chuj');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `schoolinformation`
--

CREATE TABLE `schoolinformation` (
  `schoolName` varchar(255) NOT NULL,
  `schoolAddress` varchar(255) NOT NULL,
  `schoolPhoneNumber` varchar(19) NOT NULL,
  `schoolPrincipal` varchar(255) NOT NULL,
  `schoolEndYear` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `schoolinformation`
--

INSERT INTO `schoolinformation` (`schoolName`, `schoolAddress`, `schoolPhoneNumber`, `schoolPrincipal`, `schoolEndYear`) VALUES
('Zespół Szkół Nr 1 im. Ignacego Łukasiewicza w Gorlicach nr 1', 'ul. Wyszyńskiego 18, 38,300 Gorlice, Polska', '(0-18) 353-60-40', 'Janusz Kryca', '2021-10-30');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `subjects`
--

CREATE TABLE `subjects` (
  `subjectId` int(11) NOT NULL,
  `subjectName` varchar(255) NOT NULL,
  `teacherId` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `subjects`
--

INSERT INTO `subjects` (`subjectId`, `subjectName`, `teacherId`) VALUES
(1, 'Teoretyczna Metaamfetamina Kwantowa', '{\r\n\"id\": [\"1\"]\r\n}'),
(2, 'Matematyka', '{\r\n\"id\": [\"4\"]\r\n}'),
(3, 'Wychowanie Fizyczne', '{\r\n\"id\": [\"4\"]\r\n}'),
(4, 'Polski', '{\r\n\"id\": \"7\"\r\n}'),
(5, 'Historia', '{\r\n\"id\": \"7\"\r\n}'),
(6, 'Fizyka', '{\r\n\"rank\": \"8\"\r\n}'),
(7, 'Geografia', '{\r\n\"rank\": \"8\"\r\n}'),
(8, 'Chemia', '{\r\n\"id\": \"1\"\r\n}');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `timetables`
--

CREATE TABLE `timetables` (
  `timetableId` int(8) NOT NULL,
  `subjectId` int(8) NOT NULL,
  `teacherId` int(8) NOT NULL,
  `classId` int(8) NOT NULL,
  `classDateStart` datetime NOT NULL,
  `classDateEnd` datetime NOT NULL,
  `classDescription` text DEFAULT NULL,
  `classroom` varchar(255) NOT NULL,
  `obligatory` varchar(255) NOT NULL,
  `substituteTeacherId` int(8) DEFAULT NULL,
  `substituteSubjectId` int(8) DEFAULT NULL,
  `substituteDescription` text DEFAULT NULL,
  `substituteClassroom` varchar(255) DEFAULT NULL,
  `cancelled` tinyint(1) NOT NULL,
  `subjectNumber` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `timetables`
--

INSERT INTO `timetables` (`timetableId`, `subjectId`, `teacherId`, `classId`, `classDateStart`, `classDateEnd`, `classDescription`, `classroom`, `obligatory`, `substituteTeacherId`, `substituteSubjectId`, `substituteDescription`, `substituteClassroom`, `cancelled`, `subjectNumber`) VALUES
(1, 1, 1, 1, '2021-07-19 06:40:15', '2021-07-19 07:40:15', 'Teoretyka tworzenia metaamfetaminy', '42069', 'Tak', NULL, NULL, NULL, NULL, 0, 0),
(2, 4, 7, 1, '2021-07-30 11:15:16', '2021-07-30 12:15:16', 'Polska Polskość w Polsce na Polskiej Polszczyźnie', '2137', 'Nie', 8, 6, 'FIZYKAAA ', '2137', 0, 0),
(3, 6, 8, 1, '2021-10-14 14:09:51', '2021-10-14 15:09:51', 'Taka testowa lekcja.', '23', 'Tak', NULL, NULL, NULL, NULL, 0, 0),
(4, 2, 4, 1, '2021-10-15 14:11:33', '2021-10-15 15:11:33', 'Taka druga testowa lekcja.', '4B', 'Tak', NULL, NULL, NULL, NULL, 0, 0),
(5, 4, 7, 1, '2021-10-15 16:54:43', '2021-10-15 17:54:43', 'Omawianie Makbeta.', '2B', 'Tak', 1, 8, 'Chemia dziecki.', '3B', 0, 0),
(6, 3, 4, 1, '2021-10-15 18:57:06', '2021-10-15 19:57:06', 'WF!', 'a32', 'Tak', NULL, NULL, NULL, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `userId` int(8) NOT NULL,
  `userRank` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `userEmail` varchar(255) NOT NULL,
  `userPassword` varchar(255) NOT NULL,
  `userPesel` bigint(11) NOT NULL,
  `userCreationDate` datetime NOT NULL DEFAULT current_timestamp(),
  `userFirstName` varchar(255) NOT NULL,
  `userSecondName` varchar(255) DEFAULT NULL,
  `userLastName` varchar(255) NOT NULL,
  `userAddress` varchar(255) NOT NULL,
  `userLegitimationNumber` int(6) DEFAULT NULL,
  `userBirthDate` date NOT NULL,
  `userJoinDate` date NOT NULL,
  `userLeaveDate` date DEFAULT NULL,
  `userPhoneNumber` varchar(19) NOT NULL,
  `userGender` varchar(255) NOT NULL,
  `classId` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`userId`, `userRank`, `userEmail`, `userPassword`, `userPesel`, `userCreationDate`, `userFirstName`, `userSecondName`, `userLastName`, `userAddress`, `userLegitimationNumber`, `userBirthDate`, `userJoinDate`, `userLeaveDate`, `userPhoneNumber`, `userGender`, `classId`) VALUES
(1, '{\r\n\"rank\": [\"dyrektor\", \"nauczyciel\", \"Chemia\", \"Teoretyczna Metaamfetamina Kwantowa\"]\r\n}', 'dyrektor@gmail.com', '$2y$10$.bUOJv3Pi.hkQ1cwB.Ku7ehuCSwym30vcd6fysdpg0uVsWfJtBIv2', 12345678901, '2021-07-19 08:27:20', 'Janusz', 'Skalmar', 'Okojski', 'Dyrektorska 1', NULL, '1939-07-06', '2020-09-01', NULL, '+48123456789', 'Dyrektor', NULL),
(3, '{\r\n\"rank\": [\"uczen\"]\r\n}', 'uczen1@gmail.com', '$2y$10$AUPsiyYdtihoVybeLtbaNeZKk/Wgca3kYEP6WaGgJqbBCE9Lq9gjG', 12345678911, '2021-07-19 08:35:14', 'Pierwszy', NULL, 'Uczen', 'Uczniowska 1', 123455, '2004-07-06', '2020-09-01', NULL, '+48123456432', 'Uczeń', 1),
(4, '{\n\"rank\": [\"nauczyciel\", \"Matematyka\", \"Wychowanie Fizyczne\"]\n}', 'agnieszka@gmail.com', '$2y$10$2zndmxji9pi.nVXFbZevBuz/k27MXBoZ3KAMcgupfx.zHaOy31RMa', 12345678921, '2021-07-20 09:51:11', 'Agnieszka', 'Magda', 'Kowalska', 'Agnieszkowa 13', NULL, '1947-03-02', '2002-05-17', NULL, '+48123358678', 'Kobieta', NULL),
(7, '{\r\n\"rank\": [\"nauczyciel\", \"Polski\", \"Historia\"]\r\n}', 'grazyna@gmail.com', 'empty', 12345678931, '2021-07-21 11:09:40', 'Grażyna', 'Eustachy', 'Kołocka', 'Rombana 69', NULL, '1374-02-01', '2019-03-02', NULL, '+48373558678', 'Kobieta', NULL),
(8, '{\r\n\"rank\": [\"nauczyciel\", \"Fizyka\", \"Geografia\"]\r\n}', 'bombiaszzz@gmail.com', 'empty', 12345678941, '2021-07-21 11:12:51', 'Adam', NULL, 'Knapik', 'Czajkowska 12', NULL, '1986-05-14', '2019-07-21', NULL, '+48123453841', 'Mężczyzna', NULL),
(11, '{\r\n\"rank\": [\"uczen\"]\r\n}', 'uczen2original@gmail.com', '$2y$10$lGnXnZRlEQgg.GP2Z6iD2u3K7fLZBrDg9CIUy.7ALUTTo6EE2vsci', 12345678951, '2021-07-21 11:24:37', 'Gabriela', NULL, 'Dzban', 'Wójtowa 420', 654321, '2005-11-17', '2030-08-17', '2021-07-20', '+48721738192', 'Kobieta', 1);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendanceId`),
  ADD KEY `ids` (`subjectId`,`studentId`,`teacherId`) USING BTREE,
  ADD KEY `studentId` (`studentId`),
  ADD KEY `teacherId` (`teacherId`);

--
-- Indeksy dla tabeli `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`classId`),
  ADD KEY `ids` (`mentorId`) USING BTREE;

--
-- Indeksy dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`commentId`),
  ADD KEY `ids` (`teacherId`,`studentId`) USING BTREE,
  ADD KEY `studentId` (`studentId`);

--
-- Indeksy dla tabeli `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`examId`),
  ADD KEY `ids` (`subjectId`,`teacherId`,`classId`) USING BTREE,
  ADD KEY `teacherId` (`teacherId`),
  ADD KEY `classId` (`classId`);

--
-- Indeksy dla tabeli `freedays`
--
ALTER TABLE `freedays`
  ADD PRIMARY KEY (`freeDayId`);

--
-- Indeksy dla tabeli `gradecolumns`
--
ALTER TABLE `gradecolumns`
  ADD PRIMARY KEY (`columnId`),
  ADD KEY `classId` (`classId`);

--
-- Indeksy dla tabeli `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`gradeId`),
  ADD KEY `ids` (`teacherId`,`studentId`,`subjectId`,`classId`) USING BTREE,
  ADD KEY `studentId` (`studentId`),
  ADD KEY `subjectId` (`subjectId`),
  ADD KEY `classId` (`classId`);

--
-- Indeksy dla tabeli `homework`
--
ALTER TABLE `homework`
  ADD PRIMARY KEY (`homeworkId`),
  ADD KEY `ids` (`classId`,`subjectId`,`teacherId`) USING BTREE,
  ADD KEY `teacherId` (`teacherId`),
  ADD KEY `subjectId` (`subjectId`);

--
-- Indeksy dla tabeli `meetings`
--
ALTER TABLE `meetings`
  ADD PRIMARY KEY (`meetingId`),
  ADD KEY `ids` (`teacherId`) USING BTREE;

--
-- Indeksy dla tabeli `mentors`
--
ALTER TABLE `mentors`
  ADD PRIMARY KEY (`mentorId`),
  ADD KEY `ids` (`teacherId`) USING BTREE;

--
-- Indeksy dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`messageId`),
  ADD KEY `ids` (`senderId`) USING BTREE;

--
-- Indeksy dla tabeli `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`subjectId`);

--
-- Indeksy dla tabeli `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`timetableId`),
  ADD KEY `substituteTeacherId` (`substituteTeacherId`,`subjectId`,`teacherId`,`classId`,`substituteSubjectId`),
  ADD KEY `subjectId` (`subjectId`),
  ADD KEY `teacherId` (`teacherId`),
  ADD KEY `classId` (`classId`),
  ADD KEY `substituteSubjectId` (`substituteSubjectId`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userId`),
  ADD KEY `ids` (`classId`) USING BTREE,
  ADD KEY `classId` (`classId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendanceId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `classes`
--
ALTER TABLE `classes`
  MODIFY `classId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `comments`
--
ALTER TABLE `comments`
  MODIFY `commentId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT dla tabeli `exams`
--
ALTER TABLE `exams`
  MODIFY `examId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `freedays`
--
ALTER TABLE `freedays`
  MODIFY `freeDayId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT dla tabeli `gradecolumns`
--
ALTER TABLE `gradecolumns`
  MODIFY `columnId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `grades`
--
ALTER TABLE `grades`
  MODIFY `gradeId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT dla tabeli `homework`
--
ALTER TABLE `homework`
  MODIFY `homeworkId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT dla tabeli `meetings`
--
ALTER TABLE `meetings`
  MODIFY `meetingId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT dla tabeli `mentors`
--
ALTER TABLE `mentors`
  MODIFY `mentorId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `messages`
--
ALTER TABLE `messages`
  MODIFY `messageId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT dla tabeli `subjects`
--
ALTER TABLE `subjects`
  MODIFY `subjectId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT dla tabeli `timetables`
--
ALTER TABLE `timetables`
  MODIFY `timetableId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `userId` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`studentId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `attendance_ibfk_3` FOREIGN KEY (`subjectId`) REFERENCES `subjects` (`subjectId`);

--
-- Ograniczenia dla tabeli `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`mentorId`) REFERENCES `mentors` (`mentorId`);

--
-- Ograniczenia dla tabeli `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`studentId`) REFERENCES `users` (`userId`);

--
-- Ograniczenia dla tabeli `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`subjectId`) REFERENCES `subjects` (`subjectId`),
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `exams_ibfk_3` FOREIGN KEY (`classId`) REFERENCES `classes` (`classId`);

--
-- Ograniczenia dla tabeli `gradecolumns`
--
ALTER TABLE `gradecolumns`
  ADD CONSTRAINT `gradecolumns_ibfk_1` FOREIGN KEY (`classId`) REFERENCES `classes` (`classId`);

--
-- Ograniczenia dla tabeli `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`studentId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`subjectId`) REFERENCES `subjects` (`subjectId`),
  ADD CONSTRAINT `grades_ibfk_4` FOREIGN KEY (`classId`) REFERENCES `classes` (`classId`);

--
-- Ograniczenia dla tabeli `homework`
--
ALTER TABLE `homework`
  ADD CONSTRAINT `homework_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `homework_ibfk_2` FOREIGN KEY (`subjectId`) REFERENCES `subjects` (`subjectId`),
  ADD CONSTRAINT `homework_ibfk_3` FOREIGN KEY (`classId`) REFERENCES `classes` (`classId`);

--
-- Ograniczenia dla tabeli `meetings`
--
ALTER TABLE `meetings`
  ADD CONSTRAINT `meetings_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`);

--
-- Ograniczenia dla tabeli `mentors`
--
ALTER TABLE `mentors`
  ADD CONSTRAINT `mentors_ibfk_1` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`);

--
-- Ograniczenia dla tabeli `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`senderId`) REFERENCES `users` (`userId`);

--
-- Ograniczenia dla tabeli `timetables`
--
ALTER TABLE `timetables`
  ADD CONSTRAINT `timetables_ibfk_1` FOREIGN KEY (`subjectId`) REFERENCES `subjects` (`subjectId`),
  ADD CONSTRAINT `timetables_ibfk_2` FOREIGN KEY (`teacherId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `timetables_ibfk_3` FOREIGN KEY (`classId`) REFERENCES `classes` (`classId`),
  ADD CONSTRAINT `timetables_ibfk_4` FOREIGN KEY (`substituteTeacherId`) REFERENCES `users` (`userId`),
  ADD CONSTRAINT `timetables_ibfk_5` FOREIGN KEY (`substituteSubjectId`) REFERENCES `subjects` (`subjectId`);

--
-- Ograniczenia dla tabeli `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`classId`) REFERENCES `classes` (`classId`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
