-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Сен 19 2023 г., 10:38
-- Версия сервера: 10.4.28-MariaDB
-- Версия PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `good_mood2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `articles`
--

CREATE TABLE `articles` (
                            `id` bigint(20) NOT NULL,
                            `title` varchar(255) NOT NULL,
                            `description` varchar(255) NOT NULL,
                            `article_text` varchar(1000) NOT NULL,
                            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                            `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
                            `user_id` bigint(20) UNSIGNED NOT NULL,
                            `original_filename` varchar(255) NOT NULL,
                            `storage_filename` varchar(255) NOT NULL,
                            `media_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `articles`
--

INSERT INTO `articles` (`id`, `title`, `description`, `article_text`, `created_at`, `updated_at`, `user_id`, `original_filename`, `storage_filename`, `media_type`) VALUES
(62, 'Why tackling biodiversity loss could solve the climate crisis', 'Is tackling biodiversity loss or climate change more important? The beautiful thing that many people don’t realise is that doing the first will fix the second, writes Martin Wright', 'an a grey wolf calm the climate? Can a whale tame the skies?\r\n\r\nNo, those aren’t the chorus lines of some whimsical 70s folk song. Rather, they’re the sort of legitimate questions arising from a fascinating new study into a previously overlooked, but potentially crucial, benefit of rewilding.\r\n\r\nIt found that maintaining healthy populations of just nine key wild species (or groups of species) – including elephants and wolves, but also wildebeest, musk ox and bison, as well as marine fish, whales, sharks and sea otters – can play a vital role in controlling the carbon cycle on land and sea. How? Because in order for such creatures to thrive, they need a viable habitat. And if that’s conserved, whether in the oceans, forests, grasslands or swamps, so are the many ways in which its natural properties of sequestering and storing carbon are maintained, too.\r\nThe overall impact could be, to put it mildly, massive. Compiled by 15 scientists from eight different countries, the study concluded ', '2023-09-18 23:13:32', '2023-09-18 23:13:32', 30, 'd88c0ee5a38b6879a1283e3d536fcadf.jpg', 'f5307c35df6b26ff37c00ffa6478b360.jpg', 'image/jpeg'),
(63, 'Imagine if… green energy brings down big oil (plus, how it could happen)', 'It’s 2050. Big oil is gone and emissions are plummeting. Fantasy or near-future reality? In our ‘Imagining a better future’ series, we report from tomorrow’s world, where everything turned out fine, and talk to the experts for a present-day reality check', 'It was an unlikely soundtrack to the demise of big oil. But as BP executives filed out of the firm’s London HQ for the last time yesterday, activists boogied in the street to Whitney Houston’s I Wanna Dance With Somebody.\r\n\r\nThe choice of song was no accident. The last time emissions were this low, Houston topped the charts both sides of the Atlantic. The year was 1987.\r\n\r\nA little more than half a century later, BP has joined rivals Chevron, Shell and ExxonMobil in filing for bankruptcy. Impacted employees will be retrained by the UK government for jobs in the booming green energy sector, which has 15,000 vacancies in the UK alone.\r\n\r\n“The decarbonisation wave became a tsunami,” said energy analyst Faith Laverne.\r\n\r\nIt’s 2050. Big oil is gone and emissions are plummeting. Fantasy or near-future reality? In our ‘Imagining a better future’ series, we report from tomorrow’s world, where everything turned out fine, and talk to the experts for a present-day reality check\r\n\r\nIt was an unlik', '2023-09-19 14:34:56', '2023-09-19 14:34:56', 30, 'worker.jpg', '070bf4fa5667a748a461eccf5a9cbc32.jpg', 'image/jpeg');

-- --------------------------------------------------------

--
-- Структура таблицы `auth_codes`
--

CREATE TABLE `auth_codes` (
                              `user_id` bigint(20) UNSIGNED NOT NULL,
                              `email` varchar(50) NOT NULL,
                              `code` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `auth_codes`
--

INSERT INTO `auth_codes` (`user_id`, `email`, `code`) VALUES
(30, 'altyn_312@mail.ru', '2c7bec11873b60c530734fa328231d66'),
(34, 'altynbek290697@gmail.com', '680eee45132e1f4d1996e1d0832bd18c');

-- --------------------------------------------------------

--
-- Структура таблицы `comments`
--

CREATE TABLE `comments` (
                            `id` bigint(20) NOT NULL,
                            `comment_text` varchar(255) NOT NULL,
                            `username` varchar(50) NOT NULL,
                            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                            `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
                            `user_id` bigint(20) UNSIGNED NOT NULL,
                            `article_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `comments`
--

INSERT INTO `comments` (`id`, `comment_text`, `username`, `created_at`, `updated_at`, `user_id`, `article_id`) VALUES
(2, 'asd', 'Altyn', '2023-09-19 00:37:11', '2023-09-19 00:37:11', 30, 62);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
                         `id` bigint(20) UNSIGNED NOT NULL,
                         `email` varchar(255) NOT NULL,
                         `password` varchar(255) NOT NULL,
                         `username` varchar(50) NOT NULL,
                         `role` varchar(20) NOT NULL DEFAULT 'user',
                         `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                         `email_verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `role`, `created_at`, `email_verified`) VALUES
(30, 'altyn_312@mail.ru', '$2y$12$8nYBQE9rxRZtSN9eMu9u2.NOS134YJSy7oq3CIFNNhd8UUvdc0R3K', 'Altyn', 'admin', '2023-09-18 16:11:44', 1),
(34, 'altynbek290697@gmail.com', '$2y$12$2mN7K0wy9sFhkoIEdRFGBuv5igF783/4OunV5TDtdGiJTdocu/Fte', 'Alex', 'user', '2023-09-19 00:54:14', 0);

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `articles`
--
ALTER TABLE `articles`
    ADD PRIMARY KEY (`id`),
  ADD KEY `articles_ibfk_1` (`user_id`);

--
-- Индексы таблицы `auth_codes`
--
ALTER TABLE `auth_codes`
    ADD KEY `email` (`email`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
    ADD PRIMARY KEY (`id`),
  ADD KEY `comments_ibfk_1` (`article_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
    ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `articles`
--
ALTER TABLE `articles`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `articles`
--
ALTER TABLE `articles`
    ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `auth_codes`
--
ALTER TABLE `auth_codes`
    ADD CONSTRAINT `auth_codes_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `auth_codes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
    ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
