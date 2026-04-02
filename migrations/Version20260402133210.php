<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260402133210 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `k24_contact_form` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, remote VARCHAR(255) DEFAULT NULL, is_deleted TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_link` (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) DEFAULT NULL, token VARCHAR(100) NOT NULL, url LONGTEXT NOT NULL, counter INT NOT NULL, is_fave TINYINT NOT NULL, is_public TINYINT NOT NULL, is_reported TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, collection_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E51F8CB35F37A13B (token), INDEX IDX_E51F8CB3A76ED395 (user_id), INDEX IDX_E51F8CB3514956FD (collection_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_link_collection` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, is_default TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_1D1FC2E2A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_link_statistic` (id INT AUTO_INCREMENT NOT NULL, browser_name VARCHAR(100) NOT NULL, browser_lang VARCHAR(20) NOT NULL, platform VARCHAR(30) NOT NULL, referer VARCHAR(150) NOT NULL, is_mobile TINYINT NOT NULL, ip_address VARCHAR(100) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, link_id INT DEFAULT NULL, INDEX IDX_38A48ED7ADA40271 (link_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_malicious_url` (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, domain VARCHAR(255) DEFAULT NULL, counter INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_profile` (id INT AUTO_INCREMENT NOT NULL, avatar_name VARCHAR(255) NOT NULL, size INT NOT NULL, extention VARCHAR(20) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_9794F750A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_social_profile` (id INT AUTO_INCREMENT NOT NULL, platform VARCHAR(70) NOT NULL, username VARCHAR(70) NOT NULL, url VARCHAR(200) NOT NULL, is_statistics_seen TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_B06667ADA76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_social_profile_setting` (id INT AUTO_INCREMENT NOT NULL, main_name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, count_views INT NOT NULL, is_public TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_5A0F3D80BE904C64 (main_name), UNIQUE INDEX UNIQ_5A0F3D80A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_social_profile_statistics` (id INT AUTO_INCREMENT NOT NULL, browser_name VARCHAR(50) NOT NULL, browser_lang VARCHAR(30) NOT NULL, platform VARCHAR(50) NOT NULL, is_mobil TINYINT NOT NULL, ip_adress VARCHAR(150) NOT NULL, is_seen TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, social_profile_id INT NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_71C5BD952A9A07A (social_profile_id), INDEX IDX_71C5BD95A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_social_profile_visitor` (id INT AUTO_INCREMENT NOT NULL, visitor_uuid VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_F9E6487A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_temp_user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_C632312A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, token VARCHAR(100) DEFAULT NULL, is_verified TINYINT NOT NULL, is_deleted TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE `k24_user_setting` (id INT AUTO_INCREMENT NOT NULL, allow_duplicated_urls TINYINT NOT NULL, allow_link_alias TINYINT NOT NULL, allow_redirect_after_new_link TINYINT NOT NULL, reset_private_clicks TINYINT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, user_id INT NOT NULL, INDEX IDX_CB2CB439A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE `k24_link` ADD CONSTRAINT FK_E51F8CB3A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_link` ADD CONSTRAINT FK_E51F8CB3514956FD FOREIGN KEY (collection_id) REFERENCES `k24_link_collection` (id)');
        $this->addSql('ALTER TABLE `k24_link_collection` ADD CONSTRAINT FK_1D1FC2E2A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_link_statistic` ADD CONSTRAINT FK_38A48ED7ADA40271 FOREIGN KEY (link_id) REFERENCES `k24_link` (id)');
        $this->addSql('ALTER TABLE `k24_profile` ADD CONSTRAINT FK_9794F750A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_social_profile` ADD CONSTRAINT FK_B06667ADA76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_social_profile_setting` ADD CONSTRAINT FK_5A0F3D80A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_social_profile_statistics` ADD CONSTRAINT FK_71C5BD952A9A07A FOREIGN KEY (social_profile_id) REFERENCES `k24_social_profile` (id)');
        $this->addSql('ALTER TABLE `k24_social_profile_statistics` ADD CONSTRAINT FK_71C5BD95A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_social_profile_visitor` ADD CONSTRAINT FK_F9E6487A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_temp_user` ADD CONSTRAINT FK_C632312A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('ALTER TABLE `k24_user_setting` ADD CONSTRAINT FK_CB2CB439A76ED395 FOREIGN KEY (user_id) REFERENCES `k24_user` (id)');
        $this->addSql('DROP TABLE urls');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE urls (id INT UNSIGNED AUTO_INCREMENT NOT NULL, url VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_0900_ai_ci`, created DATETIME NOT NULL, accessed DATETIME DEFAULT NULL, hits INT UNSIGNED DEFAULT 0 NOT NULL, UNIQUE INDEX url (url), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_0900_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `k24_link` DROP FOREIGN KEY FK_E51F8CB3A76ED395');
        $this->addSql('ALTER TABLE `k24_link` DROP FOREIGN KEY FK_E51F8CB3514956FD');
        $this->addSql('ALTER TABLE `k24_link_collection` DROP FOREIGN KEY FK_1D1FC2E2A76ED395');
        $this->addSql('ALTER TABLE `k24_link_statistic` DROP FOREIGN KEY FK_38A48ED7ADA40271');
        $this->addSql('ALTER TABLE `k24_profile` DROP FOREIGN KEY FK_9794F750A76ED395');
        $this->addSql('ALTER TABLE `k24_social_profile` DROP FOREIGN KEY FK_B06667ADA76ED395');
        $this->addSql('ALTER TABLE `k24_social_profile_setting` DROP FOREIGN KEY FK_5A0F3D80A76ED395');
        $this->addSql('ALTER TABLE `k24_social_profile_statistics` DROP FOREIGN KEY FK_71C5BD952A9A07A');
        $this->addSql('ALTER TABLE `k24_social_profile_statistics` DROP FOREIGN KEY FK_71C5BD95A76ED395');
        $this->addSql('ALTER TABLE `k24_social_profile_visitor` DROP FOREIGN KEY FK_F9E6487A76ED395');
        $this->addSql('ALTER TABLE `k24_temp_user` DROP FOREIGN KEY FK_C632312A76ED395');
        $this->addSql('ALTER TABLE `k24_user_setting` DROP FOREIGN KEY FK_CB2CB439A76ED395');
        $this->addSql('DROP TABLE `k24_contact_form`');
        $this->addSql('DROP TABLE `k24_link`');
        $this->addSql('DROP TABLE `k24_link_collection`');
        $this->addSql('DROP TABLE `k24_link_statistic`');
        $this->addSql('DROP TABLE `k24_malicious_url`');
        $this->addSql('DROP TABLE `k24_profile`');
        $this->addSql('DROP TABLE `k24_social_profile`');
        $this->addSql('DROP TABLE `k24_social_profile_setting`');
        $this->addSql('DROP TABLE `k24_social_profile_statistics`');
        $this->addSql('DROP TABLE `k24_social_profile_visitor`');
        $this->addSql('DROP TABLE `k24_temp_user`');
        $this->addSql('DROP TABLE `k24_user`');
        $this->addSql('DROP TABLE `k24_user_setting`');
    }
}
