<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616204636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, order_id_id INT NOT NULL, count INT NOT NULL, price DOUBLE PRECISION NOT NULL, date_paper DATE NOT NULL, date_end DATE DEFAULT NULL, INDEX IDX_B3BA5A5AFCDAEAAA (order_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_nomenclature (products_id INT NOT NULL, nomenclature_id INT NOT NULL, INDEX IDX_1EBDA1FC6C8A81A9 (products_id), INDEX IDX_1EBDA1FC90BFD4B8 (nomenclature_id), PRIMARY KEY(products_id, nomenclature_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES orders (id)');
        $this->addSql('ALTER TABLE products_nomenclature ADD CONSTRAINT FK_1EBDA1FC6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_nomenclature ADD CONSTRAINT FK_1EBDA1FC90BFD4B8 FOREIGN KEY (nomenclature_id) REFERENCES nomenclature (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products_nomenclature DROP FOREIGN KEY FK_1EBDA1FC6C8A81A9');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE products_nomenclature');
    }
}
