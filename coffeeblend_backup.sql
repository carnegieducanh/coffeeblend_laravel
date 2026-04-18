-- CoffeeBlend Database Backup
-- Generated: 2026-03-21 13:48:46

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `admins`;
CREATE TABLE `admins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES ('1', 'Mr Coffeeblend', 'coffeeblend@exam.com', '$2y$12$kUvV9/8AhJtulPPOjyRU0OUW.BbxLyNgH4OP18Y4vadVLS80PCVRS', '2026-02-27 15:37:19', '2026-02-27 15:37:19');
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES ('2', 'Mr Admin', 'admin@exam.com', '$2y$12$aG1tsYrLm3s5g.KAXoRCFO4oIGILF/v9hXP8wNqZ2CNiaq4eRgHye', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES ('3', 'Mr Coffeeblend', 'coffeeblend@exam.com', '$2y$12$faZRMLe18d2T3XgGFWwmkuDpPwZER0mlkrAUh3OIMl4WFWw4gxhUu', '2026-02-27 15:53:11', '2026-02-27 15:53:11');
INSERT INTO `admins` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES ('4', 'Mr Admin', 'admin@exam.com', '$2y$12$sTted6AjP8KEA8tboSVo9uZDMCYsRfSRLgO4oYxuXeHPMRa2Sx3Ma', '2026-02-27 15:53:13', '2026-02-27 15:53:13');

DROP TABLE IF EXISTS `bookings`;
CREATE TABLE `bookings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Processing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache`;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cache_locks`;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `cart`;
CREATE TABLE `cart` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pro_id` int NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('1', '0001_01_01_000000_create_users_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('2', '0001_01_01_000001_create_cache_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('3', '0001_01_01_000002_create_jobs_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('4', '2024_01_01_000001_create_admins_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('5', '2024_01_01_000002_create_products_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('6', '2024_01_01_000003_create_cart_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('7', '2024_01_01_000004_create_orders_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('8', '2024_01_01_000005_create_bookings_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('9', '2024_01_01_000006_create_reviews_table', '1');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('10', '2026_02_28_000001_refactor_name_columns_in_bookings_and_orders', '2');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('11', '2026_02_28_000002_add_description_ja_to_products_table', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('12', '2026_02_28_000003_seed_product_description_ja', '3');
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES ('13', '2026_03_01_084944_make_user_id_nullable_in_bookings_table', '4');

DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` int NOT NULL,
  `status` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Processing',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_ja` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('1', 'Coffee Cappuccino', 'bg_2.jpg', '5.55', 'A rich espresso-based drink topped with steamed milk foam. The perfect balance of bold coffee and creamy texture.', '濃厚なエスプレッソにスチームドミルクの泡をたっぷり乗せたドリンク。力強いコーヒーとクリーミーな口当たりの絶妙なバランス。', 'drinks', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('2', 'Mocha', 'menu-1.jpg', '6.00', 'A delightful blend of espresso, steamed milk, and chocolate syrup, topped with whipped cream. A coffee lover\'s treat.', 'エスプレッソ、スチームドミルク、チョコレートシロップを合わせ、ホイップクリームをトッピングした至福の一杯。コーヒー好きにはたまらない贅沢な味わい。', 'drinks', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('3', 'Caffe Latte', 'menu-2.jpg', '5.00', 'Smooth espresso combined with a generous amount of steamed milk and a light layer of foam. A classic and comforting choice.', 'まろやかなエスプレッソにたっぷりのスチームドミルクと薄い泡の層を合わせた一杯。定番の安心感あるクラシックコーヒー。', 'drinks', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('4', 'Americano', 'menu-3.jpg', '4.50', 'Espresso shots diluted with hot water, creating a lighter yet bold coffee experience. Simple and satisfying.', 'エスプレッソをお湯で割り、すっきりとしながらも深みのある風味に仕上げたコーヒー。シンプルで飲み応えのある一杯。', 'drinks', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('5', 'Espresso', 'menu-4.jpg', '3.50', 'A concentrated shot of pure coffee brewed by forcing hot water through finely-ground beans. Intense, aromatic, and full-bodied.', '細かく挽いた豆に高圧でお湯を通して抽出した、純粋なコーヒーの濃縮ショット。強い香りとコク深いフルボディが楽しめます。', 'drinks', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('6', 'Cheesecake', 'dessert-1.jpg', '7.00', 'Creamy and rich New York-style cheesecake with a buttery graham cracker crust. Served with a fresh berry compote.', 'バター風味のグラハムクラッカークラストに、クリーミーでリッチなニューヨークスタイルのチーズケーキ。フレッシュベリーコンポートを添えてご提供。', 'desserts', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('7', 'Pancake', 'dessert-2.jpg', '10.00', 'Fluffy, golden-brown pancakes stacked high and drizzled with maple syrup. A classic comfort food that never disappoints.', 'ふわふわでこんがり黄金色に焼いたパンケーキを高く積み上げ、メープルシロップをたっぷりかけて。何度食べても飽きない定番のコンフォートフード。', 'desserts', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('8', 'Chocolate Brownie', 'dessert-3.jpg', '6.50', 'Dense, fudgy chocolate brownie with a crispy top and gooey center. Best enjoyed warm with a scoop of vanilla ice cream.', 'さくっとした表面ととろけるような中身が絶妙な、濃厚チョコレートブラウニー。バニラアイスを添えて温かいうちにどうぞ。', 'desserts', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('9', 'Tiramisu', 'dessert-4.jpg', '8.00', 'Classic Italian dessert made with espresso-soaked ladyfingers layered with mascarpone cream and dusted with cocoa powder.', 'エスプレッソをたっぷり含ませたレディフィンガーにマスカルポーネクリームを重ね、ココアパウダーをふりかけたクラシックなイタリアンデザート。', 'desserts', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('10', 'Classic Beef Burger', 'burger-1.jpg', '12.00', 'Juicy beef patty with lettuce, tomato, pickles, and our signature sauce, served in a toasted brioche bun.', 'レタス、トマト、ピクルス、シグネチャーソースをトーストしたブリオッシュバンに挟んだジューシーなビーフパティ。', 'burgers', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('11', 'Cheese Burger', 'burger-2.jpg', '13.00', 'Our classic beef burger topped with melted cheddar cheese, caramelized onions, and crispy bacon for an extra indulgent bite.', 'とろけるチェダーチーズ、キャラメライズドオニオン、カリカリベーコンをトッピングした、クラシックビーフバーガーの贅沢バージョン。', 'burgers', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('12', 'Chicken Burger', 'burger-3.jpg', '11.00', 'Crispy fried chicken fillet with coleslaw, pickled jalapeños, and honey mustard sauce in a soft sesame bun.', 'サクサクのフライドチキンフィレにコールスロー、ピクルスのハラペーニョ、ハニーマスタードソースを合わせたごまバンサンドイッチ。', 'burgers', '2026-02-27 15:37:20', '2026-02-27 15:37:20');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('13', 'Coffee Cappuccino', 'bg_2.jpg', '5.55', 'A rich espresso-based drink topped with steamed milk foam. The perfect balance of bold coffee and creamy texture.', '濃厚なエスプレッソにスチームドミルクの泡をたっぷり乗せたドリンク。力強いコーヒーとクリーミーな口当たりの絶妙なバランス。', 'drinks', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('14', 'Mocha', 'menu-1.jpg', '6.00', 'A delightful blend of espresso, steamed milk, and chocolate syrup, topped with whipped cream. A coffee lover\'s treat.', 'エスプレッソ、スチームドミルク、チョコレートシロップを合わせ、ホイップクリームをトッピングした至福の一杯。コーヒー好きにはたまらない贅沢な味わい。', 'drinks', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('15', 'Caffe Latte', 'menu-2.jpg', '5.00', 'Smooth espresso combined with a generous amount of steamed milk and a light layer of foam. A classic and comforting choice.', 'まろやかなエスプレッソにたっぷりのスチームドミルクと薄い泡の層を合わせた一杯。定番の安心感あるクラシックコーヒー。', 'drinks', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('16', 'Americano', 'menu-3.jpg', '4.50', 'Espresso shots diluted with hot water, creating a lighter yet bold coffee experience. Simple and satisfying.', 'エスプレッソをお湯で割り、すっきりとしながらも深みのある風味に仕上げたコーヒー。シンプルで飲み応えのある一杯。', 'drinks', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('17', 'Espresso', 'menu-4.jpg', '3.50', 'A concentrated shot of pure coffee brewed by forcing hot water through finely-ground beans. Intense, aromatic, and full-bodied.', '細かく挽いた豆に高圧でお湯を通して抽出した、純粋なコーヒーの濃縮ショット。強い香りとコク深いフルボディが楽しめます。', 'drinks', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('18', 'Cheesecake', 'dessert-1.jpg', '7.00', 'Creamy and rich New York-style cheesecake with a buttery graham cracker crust. Served with a fresh berry compote.', 'バター風味のグラハムクラッカークラストに、クリーミーでリッチなニューヨークスタイルのチーズケーキ。フレッシュベリーコンポートを添えてご提供。', 'desserts', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('19', 'Pancake', 'dessert-2.jpg', '10.00', 'Fluffy, golden-brown pancakes stacked high and drizzled with maple syrup. A classic comfort food that never disappoints.', 'ふわふわでこんがり黄金色に焼いたパンケーキを高く積み上げ、メープルシロップをたっぷりかけて。何度食べても飽きない定番のコンフォートフード。', 'desserts', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('20', 'Chocolate Brownie', 'dessert-3.jpg', '6.50', 'Dense, fudgy chocolate brownie with a crispy top and gooey center. Best enjoyed warm with a scoop of vanilla ice cream.', 'さくっとした表面ととろけるような中身が絶妙な、濃厚チョコレートブラウニー。バニラアイスを添えて温かいうちにどうぞ。', 'desserts', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('21', 'Tiramisu', 'dessert-4.jpg', '8.00', 'Classic Italian dessert made with espresso-soaked ladyfingers layered with mascarpone cream and dusted with cocoa powder.', 'エスプレッソをたっぷり含ませたレディフィンガーにマスカルポーネクリームを重ね、ココアパウダーをふりかけたクラシックなイタリアンデザート。', 'desserts', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('22', 'Classic Beef Burger', 'burger-1.jpg', '12.00', 'Juicy beef patty with lettuce, tomato, pickles, and our signature sauce, served in a toasted brioche bun.', 'レタス、トマト、ピクルス、シグネチャーソースをトーストしたブリオッシュバンに挟んだジューシーなビーフパティ。', 'burgers', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('23', 'Cheese Burger', 'burger-2.jpg', '13.00', 'Our classic beef burger topped with melted cheddar cheese, caramelized onions, and crispy bacon for an extra indulgent bite.', 'とろけるチェダーチーズ、キャラメライズドオニオン、カリカリベーコンをトッピングした、クラシックビーフバーガーの贅沢バージョン。', 'burgers', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `products` (`id`, `name`, `image`, `price`, `description`, `description_ja`, `type`, `created_at`, `updated_at`) VALUES ('24', 'Chicken Burger', 'burger-3.jpg', '11.00', 'Crispy fried chicken fillet with coleslaw, pickled jalapeños, and honey mustard sauce in a soft sesame bun.', 'サクサクのフライドチキンフィレにコールスロー、ピクルスのハラペーニョ、ハニーマスタードソースを合わせたごまバンサンドイッチ。', 'burgers', '2026-02-27 15:53:13', '2026-02-27 15:53:13');

DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('1', 'Sarah Johnson', 'The cappuccino here is absolutely divine! The atmosphere is cozy and the staff are always so friendly. My go-to spot every morning.', '2026-02-27 15:37:21', '2026-02-27 15:37:21');
INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('2', 'Michael Chen', 'Best coffee in town, hands down. The mocha is perfectly balanced — not too sweet, not too bitter. The pancakes are a must-try too!', '2026-02-27 15:37:21', '2026-02-27 15:37:21');
INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('3', 'Emily Davis', 'I love how fresh everything tastes. The tiramisu paired with an espresso is the perfect combo. Will definitely be coming back!', '2026-02-27 15:37:21', '2026-02-27 15:37:21');
INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('4', 'James Wilson', 'Incredible ambiance and even better coffee. The latte art they make is stunning. A hidden gem that everyone should know about.', '2026-02-27 15:37:21', '2026-02-27 15:37:21');
INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('5', 'Sarah Johnson', 'The cappuccino here is absolutely divine! The atmosphere is cozy and the staff are always so friendly. My go-to spot every morning.', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('6', 'Michael Chen', 'Best coffee in town, hands down. The mocha is perfectly balanced — not too sweet, not too bitter. The pancakes are a must-try too!', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('7', 'Emily Davis', 'I love how fresh everything tastes. The tiramisu paired with an espresso is the perfect combo. Will definitely be coming back!', '2026-02-27 15:53:13', '2026-02-27 15:53:13');
INSERT INTO `reviews` (`id`, `name`, `review`, `created_at`, `updated_at`) VALUES ('8', 'James Wilson', 'Incredible ambiance and even better coffee. The latte art they make is stunning. A hidden gem that everyone should know about.', '2026-02-27 15:53:13', '2026-02-27 15:53:13');

DROP TABLE IF EXISTS `sessions`;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('5fZM7PtljaQ0ia85TqXkXy2o2Uruvqdf59FkBwLe', NULL, '10.16.148.194', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBAV/550.0.0.28.106;FBBV/890844927;FBDV/iPhone9,2;FBMD/iPhone;FBSN/iOS;FBSV/15.7.1;FBSS/3;FBCR/;FBID/phone;FBLC/en_US;FBOP/80]', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUmNZTjdoVW5PZU1rV0RQMFhaeWhteEdRY0lndFdNZ05LUTlKeU9iayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY29mZmVlYmxlbmQtcWJraS5vbnJlbmRlci5jb20iO3M6NToicm91dGUiO3M6NToiaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1772717135');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('6jvR6J7QoKQ5HXo7GAMKWLYEwBzODDFQqjLupxFF', NULL, '10.18.225.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBAV/550.0.0.28.106;FBBV/890844927;FBDV/iPhone9,2;FBMD/iPhone;FBSN/iOS;FBSV/15.7.1;FBSS/3;FBCR/;FBID/phone;FBLC/en_US;FBOP/80]', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY09JMVdmMmxPSUZ6Vllqd3lsOGttbEMyZW1xTEczbXEzaUtyZWZ3YSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY29mZmVlYmxlbmQtcWJraS5vbnJlbmRlci5jb20iO3M6NToicm91dGUiO3M6NToiaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1772717150');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('8LSFQzJPcJLR3MlPwoKnpABixveSpPQDsPREeeF3', NULL, '10.18.225.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/15.6.3 Mobile/15E148 Safari/604.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicnpGRXJEbHBTNEE0YVVzYWdJTUpyYTdZM2l1dWEwdklZNjI1U1RBTiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NDI6Imh0dHBzOi8vY29mZmVlYmxlbmQtcWJraS5vbnJlbmRlci5jb20vaG9tZSI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', '1772717286');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('Gh59Iu0Rzh23eaQ37srHFkAgzdpZPtnsiF1bpMTG', NULL, '10.16.147.67', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOUxpUTJTanZzd0xVbldid1dvNDNtRjMwOWc3eHJPd1B5TVNuV0NjeCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY29mZmVlYmxlbmQtcWJraS5vbnJlbmRlci5jb20iO3M6NToicm91dGUiO3M6NToiaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1774095780');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('hxbx2P2cNOVM1fBLfgZXpXszqfufLMcOw5SacRTa', NULL, '10.18.225.77', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_7_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148 [FBAN/FBIOS;FBAV/550.0.0.28.106;FBBV/890844927;FBDV/iPhone9,2;FBMD/iPhone;FBSN/iOS;FBSV/15.7.1;FBSS/3;FBCR/;FBID/phone;FBLC/en_US;FBOP/80]', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoia2tadHpVZXZxdVV2WFRHb2czeWdXU1lHUjdTNHgxYkpXcFZETHl3ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY29mZmVlYmxlbmQtcWJraS5vbnJlbmRlci5jb20iO3M6NToicm91dGUiO3M6NToiaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', '1772717139');
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES ('vihPemRlZP1qwN574IoaSLV9JNCSECRd3ZwuT2wR', NULL, '10.20.249.132', 'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/603.1.30 (KHTML, like Gecko) Version/17.5 Mobile/15A5370a Safari/602.1', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU3U1cFRSVERHdE9tZkU1aWdMUW9hZmVaZFhYQzBmTk9kdTgyRVhaSyI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHBzOi8vY29mZmVlYmxlbmQtcWJraS5vbnJlbmRlci5jb20iO3M6NToicm91dGUiO3M6NToiaW5kZXgiO319', '1772717098');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('1', 'huyducanh', 'coffeeblend@exam.com', NULL, '$2y$12$R9HfptQuljsdEFoEvfy9MO1fbrjj9rSTB8ei1TeKxnUu5SjSxd3PS', NULL, '2026-02-27 15:37:14', '2026-02-27 15:37:14');
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES ('2', 'ducanh', 'ilovephp@exam.com', NULL, '$2y$12$5h38aWRpPRN1xp8LQCm4OeEKdf8trb17vlNHbpUJ4Oi/GU5P6cVpm', NULL, '2026-02-27 15:37:16', '2026-02-27 15:37:16');

SET FOREIGN_KEY_CHECKS=1;
