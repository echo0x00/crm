<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210705165756 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products ADD nomenclature_id INT NOT NULL');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A90BFD4B8 FOREIGN KEY (nomenclature_id) REFERENCES nomenclature (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5A90BFD4B8 ON products (nomenclature_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A90BFD4B8');
        $this->addSql('DROP INDEX IDX_B3BA5A5A90BFD4B8 ON products');
        $this->addSql('ALTER TABLE products DROP nomenclature_id');
    }
}
