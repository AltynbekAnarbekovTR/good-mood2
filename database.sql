-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Окт 09 2023 г., 18:01
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
                            `article_text` varchar(5000) NOT NULL,
                            `created_at` datetime NOT NULL DEFAULT current_timestamp(),
                            `updated_at` datetime NOT NULL DEFAULT current_timestamp(),
                            `user_id` bigint(20) UNSIGNED NOT NULL,
                            `original_filename` varchar(255) NOT NULL,
                            `storage_filename` varchar(255) NOT NULL,
                            `media_type` varchar(255) NOT NULL,
                            `categories` varchar(1000) DEFAULT NULL,
                            `main_for` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `article_category`
--

CREATE TABLE `article_category` (
                                    `article_id` bigint(20) NOT NULL,
                                    `category_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `auth_codes`
--

CREATE TABLE `auth_codes` (
                              `id` int(20) NOT NULL,
                              `user_id` bigint(20) UNSIGNED NOT NULL,
                              `email` varchar(50) NOT NULL,
                              `code` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
                              `id` bigint(20) NOT NULL,
                              `title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`id`, `title`) VALUES
(1, 'Society'),
(2, 'Environment'),
(3, 'Lifestyle'),
(4, 'Science'),
(5, 'Economics'),
(6, 'Opinion'),
(7, 'World');

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
                         `email_verified` tinyint(1) NOT NULL DEFAULT 0,
                         `original_filename` varchar(255) DEFAULT NULL,
                         `storage_filename` varchar(255) DEFAULT NULL,
                         `media_type` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `role`, `created_at`, `email_verified`, `original_filename`, `storage_filename`, `media_type`) VALUES
(49, 'altyn_312@mail.ru', '$2y$12$BfiqMtTGMi2dt.T0cLRyn.n3wWoeeBGhZLh0ngROasTxvEYgt0MmC', 'Altyn', 'admin', '2023-10-01 22:38:29', 1, NULL, NULL, NULL);

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
-- Индексы таблицы `article_category`
--
ALTER TABLE `article_category`
    ADD KEY `article_id` (`article_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `auth_codes`
--
ALTER TABLE `auth_codes`
    ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `auth_codes_ibfk_1` (`email`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
    ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `comments`
--
ALTER TABLE `comments`
    ADD PRIMARY KEY (`id`),
  ADD KEY `comments_ibfk_1` (`article_id`),
  ADD KEY `comments_ibfk_2` (`user_id`);

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
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=118;

--
-- AUTO_INCREMENT для таблицы `auth_codes`
--
ALTER TABLE `auth_codes`
    MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `comments`
--
ALTER TABLE `comments`
    MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
    MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `articles`
--
ALTER TABLE `articles`
    ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `article_category`
--
ALTER TABLE `article_category`
    ADD CONSTRAINT `article_category_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `article_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ограничения внешнего ключа таблицы `auth_codes`
--
ALTER TABLE `auth_codes`
    ADD CONSTRAINT `auth_codes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ограничения внешнего ключа таблицы `comments`
--
ALTER TABLE `comments`
    ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
