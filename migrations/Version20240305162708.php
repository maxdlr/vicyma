<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305162708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lodging ADD reservation_status_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lodging ADD CONSTRAINT FK_8D35182A71B06122 FOREIGN KEY (reservation_status_id) REFERENCES reservation_status (id)');
        $this->addSql('CREATE INDEX IDX_8D35182A71B06122 ON lodging (reservation_status_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE reservation_status');
        $this->addSql('ALTER TABLE lodging DROP FOREIGN KEY FK_8D35182A71B06122');
        $this->addSql('DROP INDEX IDX_8D35182A71B06122 ON lodging');
        $this->addSql('ALTER TABLE lodging DROP reservation_status_id');
    }
}
