<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230425072540 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE banned_ip (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', perimeter_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', ip_address VARCHAR(255) NOT NULL, INDEX IDX_7E6140D77570A4C (perimeter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE banned_ip ADD CONSTRAINT FK_7E6140D77570A4C FOREIGN KEY (perimeter_id) REFERENCES perimeter (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE banned_ip DROP FOREIGN KEY FK_7E6140D77570A4C');
        $this->addSql('DROP TABLE banned_ip');
    }
}
