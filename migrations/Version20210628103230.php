<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210628103230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address_billing DROP FOREIGN KEY FK_B7C7BB32BF323F2F');
        $this->addSql('DROP INDEX IDX_B7C7BB32BF323F2F ON address_billing');
        $this->addSql('ALTER TABLE address_billing DROP order_address_billing_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address_billing ADD order_address_billing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address_billing ADD CONSTRAINT FK_B7C7BB32BF323F2F FOREIGN KEY (order_address_billing_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_B7C7BB32BF323F2F ON address_billing (order_address_billing_id)');
    }
}
