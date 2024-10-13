<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241013172745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE malicious_url DROP FOREIGN KEY FK_28104858A76ED395');
        $this->addSql('DROP INDEX IDX_28104858A76ED395 ON malicious_url');
        $this->addSql('ALTER TABLE malicious_url DROP user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE malicious_url ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE malicious_url ADD CONSTRAINT FK_28104858A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_28104858A76ED395 ON malicious_url (user_id)');
    }
}
