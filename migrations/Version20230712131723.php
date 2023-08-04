<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230712131723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customers_id INT NOT NULL, customers_name VARCHAR(255) NOT NULL, customers_company VARCHAR(255) NOT NULL, customers_street_address VARCHAR(255) NOT NULL, customers_suburd VARCHAR(255) NOT NULL, customers_city VARCHAR(255) NOT NULL, customers_post_code VARCHAR(255) NOT NULL, customers_country VARCHAR(255) NOT NULL, customers_telephone VARCHAR(255) NOT NULL, customers_telportable VARCHAR(255) NOT NULL, customers_email_address VARCHAR(255) NOT NULL, customers_email_address2 VARCHAR(255) DEFAULT NULL, customers_email_address3 VARCHAR(255) DEFAULT NULL, delivery_name VARCHAR(255) NOT NULL, delivery_company VARCHAR(255) NOT NULL, delivery_telephone VARCHAR(255) NOT NULL, delivery_street_address VARCHAR(255) NOT NULL, delivery_suburd VARCHAR(255) NOT NULL, delivery_city VARCHAR(255) NOT NULL, delivery_post_code VARCHAR(255) NOT NULL, delivery_code_porte VARCHAR(255) NOT NULL, delivery_country_code VARCHAR(255) NOT NULL, billing_name VARCHAR(255) NOT NULL, billing_company VARCHAR(255) NOT NULL, billing_street_address VARCHAR(255) NOT NULL, billing_suburd VARCHAR(255) NOT NULL, billing_city VARCHAR(255) NOT NULL, billing_post_code VARCHAR(255) NOT NULL, billing_country_code INT NOT NULL, payment_method VARCHAR(255) NOT NULL, last_modified DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_purchased DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', orders_status SMALLINT NOT NULL, id_currencies INT NOT NULL, currency_value NUMERIC(14, 6) NOT NULL, type_file INT NOT NULL, id_case VARCHAR(255) NOT NULL, id_dossier_depart_fab INT NOT NULL, id_designer_user INT NOT NULL, intitule VARCHAR(255) NOT NULL, status_regl INT NOT NULL, status_fact INT NOT NULL, status_ht INT NOT NULL, graphiste VARCHAR(255) NOT NULL, id_category_root_lgi INT DEFAULT NULL, id_product_lgi INT DEFAULT NULL, id_product_host INT DEFAULT NULL, order_selection INT DEFAULT NULL, loss DOUBLE PRECISION NOT NULL, order_option_order_id INT NOT NULL, count_for_customer INT NOT NULL, ord_avis_sending_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ord_id_coefficient_marge_used INT NOT NULL, datetime_purchased DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', datetime_last_modified DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', credit_used DOUBLE PRECISION NOT NULL, text_shipping LONGTEXT NOT NULL, a_delivery_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taorder_supplier_order (id INT AUTO_INCREMENT NOT NULL, id_order INT NOT NULL, id_supplier_order INT NOT NULL, price_without_tax DOUBLE PRECISION NOT NULL, delivery_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', job_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE taorder_supplier_order');
    }
}
