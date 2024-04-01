<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401124740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE link (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, token VARCHAR(100) NOT NULL, url VARCHAR(255) NOT NULL, counter INT NOT NULL, is_public TINYINT(1) NOT NULL, is_reported TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, title VARCHAR(255) DEFAULT NULL, INDEX IDX_36AC99F1A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE link_statistic (id INT AUTO_INCREMENT NOT NULL, link_id INT DEFAULT NULL, browser_name VARCHAR(100) NOT NULL, browser_lang VARCHAR(20) NOT NULL, platform VARCHAR(30) NOT NULL, referer VARCHAR(150) NOT NULL, is_mobile TINYINT(1) NOT NULL, ip_address VARCHAR(100) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_86EE96E8ADA40271 (link_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(150) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, token VARCHAR(100) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, updated_at DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE link ADD CONSTRAINT FK_36AC99F1A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE link_statistic ADD CONSTRAINT FK_86EE96E8ADA40271 FOREIGN KEY (link_id) REFERENCES link (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE link DROP FOREIGN KEY FK_36AC99F1A76ED395');
        $this->addSql('ALTER TABLE link_statistic DROP FOREIGN KEY FK_86EE96E8ADA40271');
        $this->addSql('DROP TABLE link');
        $this->addSql('DROP TABLE link_statistic');
        $this->addSql('DROP TABLE `user`');
    }
}
