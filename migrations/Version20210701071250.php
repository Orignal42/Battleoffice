<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210701071250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE client_id client_id INT DEFAULT NULL, CHANGE address_id address_id INT DEFAULT NULL, CHANGE payment_method payment_method VARCHAR(255) NOT NULL, CHANGE status status VARCHAR(255) NOT NULL, CHANGE id_api_response id_api_response VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` CHANGE client_id client_id INT NOT NULL, CHANGE address_id address_id INT NOT NULL, CHANGE payment_method payment_method VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status status TINYINT(1) DEFAULT NULL, CHANGE id_api_response id_api_response VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
