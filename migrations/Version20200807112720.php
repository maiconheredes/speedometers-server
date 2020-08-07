<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200807112720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_histories DROP INDEX UNIQ_8CD8E18B2EDB0489, ADD INDEX IDX_8CD8E18B2EDB0489 (cashier_id)');
        $this->addSql('ALTER TABLE payment_histories DROP INDEX UNIQ_8CD8E18B4C3A3BB, ADD INDEX IDX_8CD8E18B4C3A3BB (payment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE payment_histories DROP INDEX IDX_8CD8E18B4C3A3BB, ADD UNIQUE INDEX UNIQ_8CD8E18B4C3A3BB (payment_id)');
        $this->addSql('ALTER TABLE payment_histories DROP INDEX IDX_8CD8E18B2EDB0489, ADD UNIQUE INDEX UNIQ_8CD8E18B2EDB0489 (cashier_id)');
    }
}
