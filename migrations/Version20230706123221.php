<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706123221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tsupplier_order (id INT AUTO_INCREMENT NOT NULL, id_provider INT NOT NULL, supplier_order_id INT NOT NULL, id_supplier_order_status INT NOT NULL, supplier_order_information VARCHAR(255) NOT NULL, information_for_supplier VARCHAR(255) NOT NULL, lastupdate DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', a_delevery_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tsupplier_order_status (id INT AUTO_INCREMENT NOT NULL, icon VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, supplier_order_status_order INT NOT NULL, active INT NOT NULL, supplier_access_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ttransporter (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, fullname LONGTEXT NOT NULL, type_colis VARCHAR(255) NOT NULL, sprite VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE tsupplier_order');
        $this->addSql('DROP TABLE tsupplier_order_status');
        $this->addSql('DROP TABLE ttransporter');
    }
}
