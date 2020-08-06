<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806210935 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE payment_histories (id INT AUTO_INCREMENT NOT NULL, payment_id INT NOT NULL, cashier_id INT NOT NULL, value DOUBLE PRECISION DEFAULT \'0\' NOT NULL, payment_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_8CD8E18B4C3A3BB (payment_id), UNIQUE INDEX UNIQ_8CD8E18B2EDB0489 (cashier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE payment_histories ADD CONSTRAINT FK_8CD8E18B4C3A3BB FOREIGN KEY (payment_id) REFERENCES payments (id)');
        $this->addSql('ALTER TABLE payment_histories ADD CONSTRAINT FK_8CD8E18B2EDB0489 FOREIGN KEY (cashier_id) REFERENCES cashiers (id)');
        $this->addSql('ALTER TABLE payments CHANGE value value DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE payment_histories');
        $this->addSql('ALTER TABLE payments CHANGE value value DOUBLE PRECISION NOT NULL');
    }
}
