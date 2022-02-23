<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220127065149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP CONSTRAINT fk_b3ba5a5a3d8e604f');
        $this->addSql('DROP INDEX idx_b3ba5a5a3d8e604f');
        $this->addSql('ALTER TABLE products ADD curr_price INT NOT NULL');
        $this->addSql('ALTER TABLE products DROP parent');
        $this->addSql('ALTER TABLE products RENAME COLUMN title TO name');
        $this->addSql('ALTER TABLE products RENAME COLUMN level TO status_count');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE products ADD parent INT DEFAULT NULL');
        $this->addSql('ALTER TABLE products ADD level INT NOT NULL');
        $this->addSql('ALTER TABLE products DROP status_count');
        $this->addSql('ALTER TABLE products DROP curr_price');
        $this->addSql('ALTER TABLE products RENAME COLUMN name TO title');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT fk_b3ba5a5a3d8e604f FOREIGN KEY (parent) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_b3ba5a5a3d8e604f ON products (parent)');
    }
}
