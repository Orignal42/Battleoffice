<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210628102536 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, payment_method VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address_billing ADD order_address_billing_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address_billing ADD CONSTRAINT FK_B7C7BB32BF323F2F FOREIGN KEY (order_address_billing_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_B7C7BB32BF323F2F ON address_billing (order_address_billing_id)');
        $this->addSql('ALTER TABLE product ADD order_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF65E9B0F FOREIGN KEY (order_product_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADF65E9B0F ON product (order_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address_billing DROP FOREIGN KEY FK_B7C7BB32BF323F2F');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF65E9B0F');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP INDEX IDX_B7C7BB32BF323F2F ON address_billing');
        $this->addSql('ALTER TABLE address_billing DROP order_address_billing_id');
        $this->addSql('DROP INDEX IDX_D34A04ADF65E9B0F ON product');
        $this->addSql('ALTER TABLE product DROP order_product_id');
    }
}
