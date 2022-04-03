<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210606153403 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE orders (id INT AUTO_INCREMENT NOT NULL, agent_id INT NOT NULL, number INT NOT NULL, status INT NOT NULL, comment VARCHAR(512) NOT NULL, date_order DATETIME NOT NULL, date_delivery DATETIME NOT NULL, track_number INT NOT NULL, summ INT NOT NULL, sms_status INT NOT NULL, user_id INT NOT NULL, address VARCHAR(512) NOT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_E52FFDEE3414710B (agent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_E52FFDEE3414710B FOREIGN KEY (agent_id) REFERENCES agents (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE orders');
    }
}
