<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210628103140 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADD30DEA81');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF65E9B0F');
        $this->addSql('DROP INDEX IDX_D34A04ADD30DEA81 ON product');
        $this->addSql('DROP INDEX IDX_D34A04ADF65E9B0F ON product');
        $this->addSql('ALTER TABLE product DROP order_product_id, DROP clear_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD order_product_id INT DEFAULT NULL, ADD clear_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADD30DEA81 FOREIGN KEY (clear_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF65E9B0F FOREIGN KEY (order_product_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADD30DEA81 ON product (clear_id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADF65E9B0F ON product (order_product_id)');
    }
}
