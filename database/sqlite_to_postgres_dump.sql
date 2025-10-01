CREATE TABLE "migrations" (
  "id" SERIAL PRIMARY KEY,
  "migration" TEXT NOT NULL,
  "batch" INTEGER NOT NULL
);

INSERT INTO "migrations" ("id", "migration", "batch") VALUES (1, '0001_01_01_000000_create_users_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (2, '0001_01_01_000001_create_cache_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (3, '0001_01_01_000002_create_jobs_table', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (4, '2025_09_08_065845_create_tbl_skills', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (5, '2025_09_08_070425_create_tbl_info', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (6, '2025_09_08_070436_create_tbl_contact', 1);
INSERT INTO "migrations" ("id", "migration", "batch") VALUES (7, '2025_09_08_070444_create_tbl_projects', 1);

CREATE TABLE "users" (
  "id" SERIAL PRIMARY KEY,
  "name" TEXT NOT NULL,
  "email" TEXT NOT NULL,
  "email_verified_at" TIMESTAMP,
  "password" TEXT NOT NULL,
  "remember_token" TEXT,
  "created_at" TIMESTAMP,
  "updated_at" TIMESTAMP
);

INSERT INTO "users" ("id", "name", "email", "email_verified_at", "password", "remember_token", "created_at", "updated_at") VALUES (1, 'Demo User', 'demo@example.com', NULL, '$2y$12$azBV9ie.yAdRLAKrsk6iROJ7xWqHQwqkiBi9/sJ6hWj/4ZdSzkQNa', NULL, '2025-09-24 05:56:04', '2025-09-24 05:56:04');

CREATE TABLE "password_reset_tokens" (
  "email" TEXT NOT NULL,
  "token" TEXT NOT NULL,
  "created_at" TIMESTAMP
);


CREATE TABLE "sessions" (
  "id" TEXT NOT NULL,
  "user_id" INTEGER,
  "ip_address" TEXT,
  "user_agent" TEXT,
  "payload" TEXT NOT NULL,
  "last_activity" INTEGER NOT NULL
);

INSERT INTO "sessions" ("id", "user_id", "ip_address", "user_agent", "payload", "last_activity") VALUES ('QCxIu8U949XdZBQtLRyZP0y8VVZXyMBEqUJYDK4d', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/140.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSnVwSm43VTNQRkxlbHYzYmdLUldBbjRUWFlKSGdlcHFENTd5Nkd1VyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6Mzoiand0IjtzOjMyNDoiZXlKMGVYQWlPaUpLVjFRaUxDSmhiR2NpT2lKSVV6STFOaUo5LmV5SnBjM01pT2lKb2RIUndPaTh2TVRJM0xqQXVNQzR4T2pnd01EQXZiR2wyWlhkcGNtVXZkWEJrWVhSbElpd2lhV0YwSWpveE56VTROamt6Tmprd0xDSmxlSEFpT2pFM05UZzJPVGN5T1RBc0ltNWlaaUk2TVRjMU9EWTVNelk1TUN3aWFuUnBJam9pZGsxaWNVSjJha3B1TVhaNGEzZG5laUlzSW5OMVlpSTZJakVpTENKd2NuWWlPaUl5TTJKa05XTTRPVFE1WmpZd01HRmtZak01WlRjd01XTTBNREE0TnpKa1lqZGhOVGszTm1ZM0luMC5aX192VHp4bXdMV19ybWM1QWtLSFEtU0pQQTlJLVBHVUJlbmRZSXNZUHhvIjt9', 1758693694);

CREATE TABLE "cache" (
  "key" TEXT NOT NULL,
  "value" TEXT NOT NULL,
  "expiration" INTEGER NOT NULL
);

INSERT INTO "cache" ("key", "value", "expiration") VALUES ('aLnVZqqWpL2GSnkp', 's:7:"forever";', 2074053465);
INSERT INTO "cache" ("key", "value", "expiration") VALUES ('vMbqBvjJn1vxkwgz', 's:7:"forever";', 2074053693);

CREATE TABLE "cache_locks" (
  "key" TEXT NOT NULL,
  "owner" TEXT NOT NULL,
  "expiration" INTEGER NOT NULL
);


CREATE TABLE "jobs" (
  "id" SERIAL PRIMARY KEY,
  "queue" TEXT NOT NULL,
  "payload" TEXT NOT NULL,
  "attempts" INTEGER NOT NULL,
  "reserved_at" INTEGER,
  "available_at" INTEGER NOT NULL,
  "created_at" INTEGER NOT NULL
);


CREATE TABLE "job_batches" (
  "id" TEXT NOT NULL,
  "name" TEXT NOT NULL,
  "total_jobs" INTEGER NOT NULL,
  "pending_jobs" INTEGER NOT NULL,
  "failed_jobs" INTEGER NOT NULL,
  "failed_job_ids" TEXT NOT NULL,
  "options" TEXT,
  "cancelled_at" INTEGER,
  "created_at" INTEGER NOT NULL,
  "finished_at" INTEGER
);


CREATE TABLE "failed_jobs" (
  "id" SERIAL PRIMARY KEY,
  "uuid" TEXT NOT NULL,
  "connection" TEXT NOT NULL,
  "queue" TEXT NOT NULL,
  "payload" TEXT NOT NULL,
  "exception" TEXT NOT NULL,
  "failed_at" TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE "tbl_skills" (
  "id" SERIAL PRIMARY KEY,
  "name" TEXT NOT NULL,
  "icon_path" TEXT NOT NULL,
  "category" TEXT NOT NULL,
  "proficiency" TEXT NOT NULL,
  "sort_order" INTEGER NOT NULL DEFAULT '0',
  "status" TEXT NOT NULL DEFAULT 'active',
  "created_at" TIMESTAMP,
  "updated_at" TIMESTAMP,
  "deleted_at" TIMESTAMP
);

INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (1, 'Laravel', 'skills_logo/laravel_logo.png', 'framework', '95', 1, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (2, 'PHP', 'skills_logo/php_logo.png', 'backend', '92', 2, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (3, 'Livewire', 'skills_logo/livewire_logo.png', 'framework', '90', 3, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (4, 'JavaScript', 'skills_logo/javascript_logo.png', 'frontend', '88', 4, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (5, 'TailwindCSS', 'skills_logo/tailwindcss_logo.png', 'frontend', '90', 5, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (6, 'Alpine.js', 'skills_logo/alpine_logo.png', 'frontend', '85', 6, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (7, 'HTML5', 'skills_logo/html_logo.png', 'frontend', '95', 7, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (8, 'CSS3', 'skills_logo/css_logo.png', 'frontend', '90', 8, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (9, 'JQuery', 'skills_logo/jquery_logo.png', 'frontend', '90', 8, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (10, 'MySQL', 'skills_logo/mysql_logo.png', 'database', '87', 9, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (11, 'Git', 'skills_logo/github_logo.png', 'general', '88', 11, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);
INSERT INTO "tbl_skills" ("id", "name", "icon_path", "category", "proficiency", "sort_order", "status", "created_at", "updated_at", "deleted_at") VALUES (12, 'Figma', 'skills_logo/figma_logo.png', 'general', '75', 11, 'active', '2025-09-24 05:53:16', '2025-09-24 05:53:16', NULL);

CREATE TABLE "tbl_info" (
  "id" SERIAL PRIMARY KEY,
  "first_name" TEXT NOT NULL,
  "last_name" TEXT NOT NULL,
  "description" TEXT,
  "bio" TEXT,
  "job_position" TEXT,
  "created_at" TIMESTAMP,
  "updated_at" TIMESTAMP,
  "deleted_at" TIMESTAMP
);


CREATE TABLE "tbl_contact" (
  "id" SERIAL PRIMARY KEY,
  "type" TEXT NOT NULL,
  "details" TEXT NOT NULL,
  "icon" TEXT,
  "status" TEXT NOT NULL DEFAULT 'active',
  "created_at" TIMESTAMP,
  "updated_at" TIMESTAMP,
  "deleted_at" TIMESTAMP
);


CREATE TABLE "tbl_projects" (
  "id" SERIAL PRIMARY KEY,
  "acronym" TEXT,
  "title" TEXT NOT NULL,
  "description" TEXT,
  "link" TEXT,
  "image" TEXT,
  "project_status" TEXT,
  "status" TEXT NOT NULL DEFAULT 'active',
  "made_of" TEXT,
  "created_at" TIMESTAMP,
  "updated_at" TIMESTAMP,
  "deleted_at" TIMESTAMP
);

INSERT INTO "tbl_projects" ("id", "acronym", "title", "description", "link", "image", "project_status", "status", "made_of", "created_at", "updated_at", "deleted_at") VALUES (1, 'WMS', 'Warehouse Management System', 'A Warehouse Management System (WMS) is software that keeps a warehouse organized by tracking inventory in real time, from arrival to shipping. It helps manage stock levels, locations, and movements, ensuring efficient operations and accurate order fulfillment.', NULL, '/project_logo/warehouse_logo.png', 'completed', 'active', '["PHP","MySQL","HTML","CSS","Bootstrap","JavaScript","jQuery"]', '2025-09-24 05:53:08', '2025-09-24 05:53:08', NULL);
INSERT INTO "tbl_projects" ("id", "acronym", "title", "description", "link", "image", "project_status", "status", "made_of", "created_at", "updated_at", "deleted_at") VALUES (2, 'CP', 'Customer Portal System', 'This system serves similarly to an e-commerce website, offering clients a business-to-business (B2B) storage solution where they can make inbound and outgoing transactions and utilize this portal to monitor their products on our warehouse management system.', NULL, NULL, 'completed', 'active', '["PHP","MySQL","HTML","CSS","Bootstrap","JavaScript","jQuery"]', '2025-09-24 05:53:08', '2025-09-24 05:53:08', NULL);

