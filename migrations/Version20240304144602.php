<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240304144602 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bed (id INT AUTO_INCREMENT NOT NULL, height INT NOT NULL, width INT NOT NULL, is_extra TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE lodging_bed (lodging_id INT NOT NULL, bed_id INT NOT NULL, INDEX IDX_47AE65BC87335AF1 (lodging_id), INDEX IDX_47AE65BC88688BB9 (bed_id), PRIMARY KEY(lodging_id, bed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE lodging_bed ADD CONSTRAINT FK_47AE65BC87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lodging_bed ADD CONSTRAINT FK_47AE65BC88688BB9 FOREIGN KEY (bed_id) REFERENCES bed (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lodging_bed DROP FOREIGN KEY FK_47AE65BC87335AF1');
        $this->addSql('ALTER TABLE lodging_bed DROP FOREIGN KEY FK_47AE65BC88688BB9');
        $this->addSql('DROP TABLE bed');
        $this->addSql('DROP TABLE lodging_bed');
    }
}
