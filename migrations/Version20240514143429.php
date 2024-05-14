<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240514143429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F87335AF1');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F87335AF1');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F87335AF1 FOREIGN KEY (lodging_id) REFERENCES lodging (id)');
    }
}
