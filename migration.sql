-- ═══════════════════════════════════════════════════════════════════════════
-- DMCE — Certificats d'hébergement — Script SQL complet
-- À exécuter sur old_dmce APRÈS backup
-- ═══════════════════════════════════════════════════════════════════════════

-- 1. TABLE HEBERGEURS (Congolais)
-- ─────────────────────────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `hebergeurs` (
    `id`                    BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    `code_hebergeur`        VARCHAR(30) NOT NULL COMMENT 'Format: HEB-AAMMJJ-XXXXX',
    `nom`                   VARCHAR(100) NOT NULL,
    `prenom`                VARCHAR(100) NOT NULL,
    `sexe`                  ENUM('Masculin','Féminin') NOT NULL,
    `date_naissance`        DATE NULL,
    `lieu_naissance`        VARCHAR(150) NULL,
    `nationalite`           VARCHAR(100) NOT NULL DEFAULT 'Congolaise',
    `telephone`             VARCHAR(50) NOT NULL,
    `email`                 VARCHAR(100) NULL,
    `quartiers_id`          BIGINT(20) UNSIGNED NOT NULL,
    `avenue_rue`            VARCHAR(200) NOT NULL,
    `numero_adresse`        VARCHAR(50) NOT NULL,
    `type_piece`            VARCHAR(100) NULL COMMENT 'CNI, Passeport, etc.',
    `numero_piece`          VARCHAR(100) NULL,
    `date_emission_piece`   DATE NULL,
    `date_expiration_piece` DATE NULL,
    `profession`            VARCHAR(150) NULL,
    `created_by`            BIGINT(20) UNSIGNED NOT NULL,
    `created_at`            TIMESTAMP NULL,
    `updated_at`            TIMESTAMP NULL,
    `deleted_at`            TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `hebergeurs_code_hebergeur_unique` (`code_hebergeur`),
    KEY `hebergeurs_quartiers_id_foreign` (`quartiers_id`),
    KEY `hebergeurs_created_by_foreign` (`created_by`),
    CONSTRAINT `hebergeurs_quartiers_id_foreign`
        FOREIGN KEY (`quartiers_id`) REFERENCES `quartiers` (`id`),
    CONSTRAINT `hebergeurs_created_by_foreign`
        FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- 2. COLONNES SUR IMPETRANTS
-- ─────────────────────────────────────────────────────────────────────────
ALTER TABLE `impetrants`
    ADD COLUMN `est_hebergeur`  TINYINT(1) NOT NULL DEFAULT 0
        COMMENT '1 si cet impétrant est aussi hébergeur'
        AFTER `unique_string`,
    ADD COLUMN `code_hebergeur` VARCHAR(30) NULL
        COMMENT 'Format: HEB-AAMMJJ-XXXXX'
        AFTER `est_hebergeur`;


-- 3. COLONNES SUR EMPLOYEURS
-- ─────────────────────────────────────────────────────────────────────────
ALTER TABLE `employeurs`
    ADD COLUMN `est_hebergeur`  TINYINT(1) NOT NULL DEFAULT 0
        COMMENT '1 si cette société peut héberger'
        AFTER `id`,
    ADD COLUMN `code_hebergeur` VARCHAR(30) NULL
        COMMENT 'Format: HEB-AAMMJJ-XXXXX'
        AFTER `est_hebergeur`;


-- 4. REFACTORING CERTIFICATS_HEBERGEMENT
-- ─────────────────────────────────────────────────────────────────────────

-- 4a. Ajouter les nouvelles colonnes
ALTER TABLE `certificats_hebergement`
    ADD COLUMN `hebergeur_type`        ENUM('Congolais','Etranger','Societe') NULL
        COMMENT 'Type hébergeur'
        AFTER `numero_certificat`,
    ADD COLUMN `hebergeur_id`          BIGINT(20) UNSIGNED NULL
        COMMENT 'FK polymorphique'
        AFTER `hebergeur_type`,
    ADD COLUMN `heberge_impetrant_id`  BIGINT(20) UNSIGNED NULL
        COMMENT 'FK vers impetrants.id'
        AFTER `hebergeur_id`,
    ADD COLUMN `demande_id`            BIGINT(20) UNSIGNED NULL
        COMMENT 'FK vers demandes.id'
        AFTER `heberge_impetrant_id`;

-- 4b. Ajouter les contraintes FK
ALTER TABLE `certificats_hebergement`
    ADD CONSTRAINT `cert_heb_heberge_fk`
        FOREIGN KEY (`heberge_impetrant_id`) REFERENCES `impetrants` (`id`)
        ON DELETE SET NULL,
    ADD CONSTRAINT `cert_heb_demande_fk`
        FOREIGN KEY (`demande_id`) REFERENCES `demandes` (`id`)
        ON DELETE SET NULL;

-- 4c. Supprimer les colonnes plates redondantes
ALTER TABLE `certificats_hebergement`
    DROP COLUMN `hebergeur_nom`,
    DROP COLUMN `hebergeur_prenom`,
    DROP COLUMN `hebergeur_date_naissance`,
    DROP COLUMN `hebergeur_lieu_naissance`,
    DROP COLUMN `hebergeur_sexe`,
    DROP COLUMN `hebergeur_nationalite`,
    DROP COLUMN `hebergeur_type_document`,
    DROP COLUMN `hebergeur_numero_document`,
    DROP COLUMN `hebergeur_date_emission_document`,
    DROP COLUMN `hebergeur_date_expiration_document`,
    DROP COLUMN `hebergeur_telephone`,
    DROP COLUMN `hebergeur_email`,
    DROP COLUMN `hebergeur_profession`,
    DROP COLUMN `hebergeur_quartiers_id`,
    DROP COLUMN `hebergeur_avenue_rue`,
    DROP COLUMN `hebergeur_numero_adresse`,
    DROP COLUMN `heberge_nom`,
    DROP COLUMN `heberge_prenom`,
    DROP COLUMN `heberge_date_naissance`,
    DROP COLUMN `heberge_lieu_naissance`,
    DROP COLUMN `heberge_sexe`,
    DROP COLUMN `heberge_nationalite`,
    DROP COLUMN `heberge_type_document`,
    DROP COLUMN `heberge_numero_document`,
    DROP COLUMN `heberge_date_emission_document`,
    DROP COLUMN `heberge_date_expiration_document`,
    DROP COLUMN `heberge_telephone`,
    DROP COLUMN `heberge_email`;


-- 5. VÉRIFICATION FINALE
-- ─────────────────────────────────────────────────────────────────────────
SELECT 'hebergeurs créée' AS status, COUNT(*) AS lignes FROM hebergeurs;
DESCRIBE certificats_hebergement;
