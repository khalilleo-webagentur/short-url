<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520183147 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE contact_form (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, subject VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, remote VARCHAR(255) DEFAULT NULL, is_deleted TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, collection_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, token VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, counter INT NOT NULL, is_fave TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, is_reported TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_36AC99F15F37A13B (token), INDEX IDX_36AC99F1A76ED395 (user_id), INDEX IDX_36AC99F1514956FD (collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE link_collection (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) NOT NULL, is_default TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_ABC7A5C7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE link_statistic (id INT AUTO_INCREMENT NOT NULL, link_id INT DEFAULT NULL, browser_name VARCHAR(100) NOT NULL, browser_lang VARCHAR(20) NOT NULL, platform VARCHAR(30) NOT NULL, referer VARCHAR(150) NOT NULL, is_mobile TINYINT(1) NOT NULL, ip_address VARCHAR(100) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_86EE96E8ADA40271 (link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE malicious_url (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, domain VARCHAR(255) DEFAULT NULL, counter INT NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, avatar_name VARCHAR(255) NOT NULL, size INT NOT NULL, extention VARCHAR(20) DEFAULT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8157AA0FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE social_profile (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, platform VARCHAR(70) NOT NULL, username VARCHAR(70) NOT NULL, url VARCHAR(200) NOT NULL, is_statistics_seen TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_E2C7F92A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE social_profile_setting (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, main_name VARCHAR(150) NOT NULL, description LONGTEXT DEFAULT NULL, count_views INT NOT NULL, is_public TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_53E73187BE904C64 (main_name), UNIQUE INDEX UNIQ_53E73187A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE social_profile_statistics (id INT AUTO_INCREMENT NOT NULL, social_profile_id INT NOT NULL, user_id INT DEFAULT NULL, browser_name VARCHAR(50) NOT NULL, browser_lang VARCHAR(30) NOT NULL, platform VARCHAR(50) NOT NULL, is_mobil TINYINT(1) NOT NULL, ip_adress VARCHAR(150) NOT NULL, is_seen TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_76EE8E5F2A9A07A (social_profile_id), INDEX IDX_76EE8E5FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE social_profile_visitor (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, visitor_uuid VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_6766880A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE temp_user (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_249A5903A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, token VARCHAR(100) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, is_deleted TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_setting (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, allow_duplicated_urls TINYINT(1) NOT NULL, allow_link_alias TINYINT(1) NOT NULL, allow_redirect_after_new_link TINYINT(1) NOT NULL, reset_private_clicks TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C779A692A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE link ADD CONSTRAINT FK_36AC99F1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE link ADD CONSTRAINT FK_36AC99F1514956FD FOREIGN KEY (collection_id) REFERENCES link_collection (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE link_collection ADD CONSTRAINT FK_ABC7A5C7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE link_statistic ADD CONSTRAINT FK_86EE96E8ADA40271 FOREIGN KEY (link_id) REFERENCES link (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE profile ADD CONSTRAINT FK_8157AA0FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile ADD CONSTRAINT FK_E2C7F92A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_setting ADD CONSTRAINT FK_53E73187A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_statistics ADD CONSTRAINT FK_76EE8E5F2A9A07A FOREIGN KEY (social_profile_id) REFERENCES social_profile (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_statistics ADD CONSTRAINT FK_76EE8E5FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_visitor ADD CONSTRAINT FK_6766880A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE temp_user ADD CONSTRAINT FK_249A5903A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_setting ADD CONSTRAINT FK_C779A692A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE link DROP FOREIGN KEY FK_36AC99F1A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE link DROP FOREIGN KEY FK_36AC99F1514956FD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE link_collection DROP FOREIGN KEY FK_ABC7A5C7A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE link_statistic DROP FOREIGN KEY FK_86EE96E8ADA40271
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE profile DROP FOREIGN KEY FK_8157AA0FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile DROP FOREIGN KEY FK_E2C7F92A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_setting DROP FOREIGN KEY FK_53E73187A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_statistics DROP FOREIGN KEY FK_76EE8E5F2A9A07A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_statistics DROP FOREIGN KEY FK_76EE8E5FA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE social_profile_visitor DROP FOREIGN KEY FK_6766880A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE temp_user DROP FOREIGN KEY FK_249A5903A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_setting DROP FOREIGN KEY FK_C779A692A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact_form
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE link
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE link_collection
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE link_statistic
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE malicious_url
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE profile
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE social_profile
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE social_profile_setting
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE social_profile_statistics
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE social_profile_visitor
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE temp_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_setting
        SQL);
    }
}
