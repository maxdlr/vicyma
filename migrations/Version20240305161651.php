<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305161651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, adult_count INT NOT NULL, child_count INT DEFAULT NULL, arrival_date DATETIME NOT NULL, departure_date DATETIME NOT NULL, price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE reservation_lodging (reservation_id INT NOT NULL, lodging_id INT NOT NULL, INDEX IDX_EAA0A3AFB83297E7 (reservation_id), INDEX IDX_EAA0A3AF87335AF1 (lodging_id), PRIMARY KEY(reservation_id, lodging_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reservation_lodging ADD CONSTRAINT FK_EAA0A3AFB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation_lodging ADD CONSTRAINT FK_EAA0A3AF87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation_lodging DROP FOREIGN KEY FK_EAA0A3AFB83297E7');
        $this->addSql('ALTER TABLE reservation_lodging DROP FOREIGN KEY FK_EAA0A3AF87335AF1');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE reservation_lodging');
    }
}
