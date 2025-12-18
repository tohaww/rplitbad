BEGIN TRANSACTION;
CREATE TABLE IF NOT EXISTS "assesor" (
	"id_assesor"	varchar NOT NULL,
	"nama"	varchar NOT NULL,
	"created_at"	datetime,
	"updated_at"	datetime,
	"password"	varchar,
	PRIMARY KEY("id_assesor")
);
CREATE TABLE IF NOT EXISTS "users" (
	"id_user"	integer NOT NULL,
	"name"	varchar NOT NULL,
	"email"	varchar NOT NULL,
	"email_verified_at"	datetime,
	"password"	varchar NOT NULL,
	"remember_token"	varchar,
	"created_at"	datetime,
	"updated_at"	datetime,
	"role"	varchar NOT NULL DEFAULT 'mahasiswa',
	"kode_referensi"	varchar,
	PRIMARY KEY("id_user" AUTOINCREMENT)
);
INSERT INTO "assesor" ("id_assesor","nama","created_at","updated_at","password") VALUES ('D345','dinda','2025-12-10 14:48:41','2025-12-16 10:49:09','$2y$12$hzCtjJxVkBICDnBDGgaMr.DdCfWJD49dEAeCp2SV3ifK2XTNSkJ0u');
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (1,'Mahasiswa Contoh','mahasiswa@example.com',NULL,'$2y$12$BBdy1bpnJqZvbNourc6/bucpemHdYPX.Uc36EPimC9cxuoi89Bpdi',NULL,'2025-12-03 13:29:00','2025-12-03 13:29:00','mahasiswa',NULL);
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (2,'Administrator','admin@example.com',NULL,'$2y$12$Onl5VSEm.kkDw1DK2CiCr.W/toEwYRedGFGhVfBCqU.GlqYDRDQfm',NULL,'2025-12-03 13:29:01','2025-12-03 13:29:01','admin',NULL);
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (3,'Angga Yunanda','angga@gmail.com',NULL,'$2y$12$jas1P4jsd.a6/rUewjExieDxuJcWpvgyYQ/62GMlmqoc/SrqS7PNK',NULL,'2025-12-03 13:31:47','2025-12-03 13:31:47','mahasiswa',NULL);
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (4,'yudi yudiman','yudi@rpl.com',NULL,'$2y$12$s2apW.j9Oo/zmzi9HFFzje1.4d29aWKlwanSn6ZnbHex2OIn5ubbi',NULL,'2025-12-03 13:47:39','2025-12-03 13:47:39','mahasiswa',NULL);
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (5,'fadli wirawan','fadli@rpl.com',NULL,'$2y$12$flE7NVn5vfdsdkNuihvgLemcdVFJ9Qvzn9EX4NjZSODmL5kAiFitW',NULL,'2025-12-03 13:53:27','2025-12-03 13:53:27','mahasiswa',NULL);
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (6,'toha','toha@rpl.com',NULL,'$2y$12$1Hqvnkgo1MGbH3L7wLfD7eXg3G8yI/pXaFEPaC.ta.Wh/83u8M862',NULL,'2025-12-05 14:43:20','2025-12-05 14:43:20','mahasiswa',NULL);
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (9,'Fadli Rajaski','rajas@tpl.com',NULL,'$2y$12$0j5R2.SLsn7Spgd9J58LqOY4rV9N0eJagejCLur9g/LYPCSNv3u8e',NULL,'2025-12-08 04:36:49','2025-12-08 04:36:49','mahasiswa','P001');
INSERT INTO "users" ("id_user","name","email","email_verified_at","password","remember_token","created_at","updated_at","role","kode_referensi") VALUES (10,'dinda','D345@asesor.local',NULL,'$2y$12$hzCtjJxVkBICDnBDGgaMr.DdCfWJD49dEAeCp2SV3ifK2XTNSkJ0u',NULL,'2025-12-10 14:53:20','2025-12-16 10:49:17','asesor',NULL);
CREATE UNIQUE INDEX IF NOT EXISTS "users_email_unique" ON "users" (
	"email"
);
COMMIT;
