<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230421084404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vulnerability ADD status VARCHAR(255) DEFAULT NULL, DROP is_solved, DROP is_visible, DROP is_new');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vulnerability ADD is_solved TINYINT(1) NOT NULL, ADD is_visible TINYINT(1) NOT NULL, ADD is_new TINYINT(1) NOT NULL, DROP status');
    }
}
