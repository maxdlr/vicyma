<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240218110149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lodging (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, capacity INT NOT NULL, room_count INT NOT NULL, surface DOUBLE PRECISION NOT NULL, bathroom_count INT NOT NULL, toilet_count INT NOT NULL, tv_service TINYINT(1) NOT NULL, washer TINYINT(1) NOT NULL, water_heater TINYINT(1) NOT NULL, parking TINYINT(1) NOT NULL, gate TINYINT(1) NOT NULL, is_animal_allowed TINYINT(1) NOT NULL, terrasse TINYINT(1) NOT NULL, terrasse_surface DOUBLE PRECISION DEFAULT NULL, floor INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE lodging');
    }
}
