<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210713121817 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE archive (id INT AUTO_INCREMENT NOT NULL, nomenclature_id INT DEFAULT NULL, count INT DEFAULT NULL, date_paper DATETIME NOT NULL, INDEX IDX_D5FC5D9C90BFD4B8 (nomenclature_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE archive ADD CONSTRAINT FK_D5FC5D9C90BFD4B8 FOREIGN KEY (nomenclature_id) REFERENCES nomenclature (id)');
        $this->addSql('ALTER TABLE nomenclature ADD color VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE archive');
        $this->addSql('ALTER TABLE nomenclature DROP color');
    }
}
