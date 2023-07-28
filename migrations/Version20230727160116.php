<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727160116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customers_id INT NOT NULL, customers_name VARCHAR(255) NOT NULL, customers_company VARCHAR(255) NOT NULL, customers_street_address VARCHAR(255) NOT NULL, customers_suburd VARCHAR(255) NOT NULL, customers_city VARCHAR(255) NOT NULL, customers_post_code VARCHAR(255) NOT NULL, customers_country VARCHAR(255) NOT NULL, customers_telephone VARCHAR(255) NOT NULL, customers_telportable VARCHAR(255) NOT NULL, customers_email_address VARCHAR(255) NOT NULL, customers_email_address2 VARCHAR(255) DEFAULT NULL, customers_email_address3 VARCHAR(255) DEFAULT NULL, delivery_name VARCHAR(255) NOT NULL, delivery_company VARCHAR(255) NOT NULL, delivery_telephone VARCHAR(255) NOT NULL, delivery_street_address VARCHAR(255) NOT NULL, delivery_suburd VARCHAR(255) NOT NULL, delivery_city VARCHAR(255) NOT NULL, delivery_post_code VARCHAR(255) NOT NULL, delivery_code_porte VARCHAR(255) NOT NULL, delivery_country_code VARCHAR(255) NOT NULL, billing_name VARCHAR(255) NOT NULL, billing_company VARCHAR(255) NOT NULL, billing_street_address VARCHAR(255) NOT NULL, billing_suburd VARCHAR(255) NOT NULL, billing_city VARCHAR(255) NOT NULL, billing_post_code VARCHAR(255) NOT NULL, billing_country_code INT NOT NULL, payment_method VARCHAR(255) NOT NULL, last_modified DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_purchased DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', orders_status SMALLINT NOT NULL, id_currencies INT NOT NULL, currency_value NUMERIC(14, 6) NOT NULL, type_file INT NOT NULL, id_case VARCHAR(255) NOT NULL, id_dossier_depart_fab INT NOT NULL, id_designer_user INT NOT NULL, intitule VARCHAR(255) NOT NULL, status_regl INT NOT NULL, status_fact INT NOT NULL, status_ht INT NOT NULL, graphiste VARCHAR(255) NOT NULL, id_category_root_lgi INT DEFAULT NULL, id_product_lgi INT DEFAULT NULL, id_product_host INT DEFAULT NULL, order_selection INT DEFAULT NULL, loss DOUBLE PRECISION NOT NULL, order_option_order_id INT NOT NULL, count_for_customer INT NOT NULL, ord_avis_sending_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ord_id_coefficient_marge_used INT NOT NULL, datetime_purchased DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', datetime_last_modified DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', credit_used DOUBLE PRECISION NOT NULL, text_shipping LONGTEXT NOT NULL, a_delivery_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tahost_cms_bloc (id INT AUTO_INCREMENT NOT NULL, t_cms_bloc VARCHAR(255) NOT NULL, t_cms_diapo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taorder_supplier_order (id INT AUTO_INCREMENT NOT NULL, t_supplier_order_id INT DEFAULT NULL, t_order_id INT DEFAULT NULL, id_order INT NOT NULL, id_supplier_order INT NOT NULL, price_without_tax DOUBLE PRECISION NOT NULL, delivery_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', job_id INT NOT NULL, INDEX IDX_A8CAE89DE00619AF (t_supplier_order_id), INDEX IDX_A8CAE89D597E7162 (t_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taproduct_host_category (id INT AUTO_INCREMENT NOT NULL, id_product_host INT NOT NULL, t_product_host VARCHAR(255) NOT NULL, id_category INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taproduct_meta (id INT AUTO_INCREMENT NOT NULL, id_parent INT NOT NULL, id_child INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tavariant_option_value (id INT AUTO_INCREMENT NOT NULL, id_host INT NOT NULL, is_active TINYINT(1) NOT NULL, var_opt_val_order INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcategory (id INT AUTO_INCREMENT NOT NULL, ordre SMALLINT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcms_bloc (id INT AUTO_INCREMENT NOT NULL, id_type INT NOT NULL, id_cms_diapo INT NOT NULL, content LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcms_diapo (id INT AUTO_INCREMENT NOT NULL, height INT NOT NULL, rep_url VARCHAR(255) NOT NULL, width INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcms_page (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, meta_title VARCHAR(255) NOT NULL, meta_description VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, statut INT NOT NULL, last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_heure_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tcombinaison (id INT AUTO_INCREMENT NOT NULL, selection VARCHAR(255) NOT NULL, date_maj DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tonline_product_rule (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tproduct_host_more_viewed (id INT AUTO_INCREMENT NOT NULL, id_product_host INT NOT NULL, counter INT NOT NULL, id_host INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ttechnical_sheet (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, id_product INT NOT NULL, id_option_value INT NOT NULL, technical_sheet_parent VARCHAR(255) NOT NULL, product_option_value_lig VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ttxt (id INT AUTO_INCREMENT NOT NULL, value LONGTEXT NOT NULL, id_host INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE taorder_supplier_order ADD CONSTRAINT FK_A8CAE89DE00619AF FOREIGN KEY (t_supplier_order_id) REFERENCES tsupplier_order (id)');
        $this->addSql('ALTER TABLE taorder_supplier_order ADD CONSTRAINT FK_A8CAE89D597E7162 FOREIGN KEY (t_order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE provider ADD master_id INT NOT NULL, ADD number_vat INT NOT NULL, ADD recoverable_vat INT NOT NULL, ADD payment VARCHAR(255) NOT NULL, ADD web_site_address VARCHAR(255) NOT NULL, ADD asset INT NOT NULL, DROP master_fournisseur_id, DROP number_tva, DROP tva_recuperable, DROP payement, DROP site_adress, DROP actif, CHANGE dir_factures dir_invoices VARCHAR(255) DEFAULT NULL, CHANGE taux_tva vatrate DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taorder_supplier_order DROP FOREIGN KEY FK_A8CAE89DE00619AF');
        $this->addSql('ALTER TABLE taorder_supplier_order DROP FOREIGN KEY FK_A8CAE89D597E7162');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE tahost_cms_bloc');
        $this->addSql('DROP TABLE taorder_supplier_order');
        $this->addSql('DROP TABLE taproduct_host_category');
        $this->addSql('DROP TABLE taproduct_meta');
        $this->addSql('DROP TABLE tavariant_option_value');
        $this->addSql('DROP TABLE tcategory');
        $this->addSql('DROP TABLE tcms_bloc');
        $this->addSql('DROP TABLE tcms_diapo');
        $this->addSql('DROP TABLE tcms_page');
        $this->addSql('DROP TABLE tcombinaison');
        $this->addSql('DROP TABLE tonline_product_rule');
        $this->addSql('DROP TABLE tproduct_host_more_viewed');
        $this->addSql('DROP TABLE ttechnical_sheet');
        $this->addSql('DROP TABLE ttxt');
        $this->addSql('ALTER TABLE provider ADD master_fournisseur_id INT NOT NULL, ADD number_tva INT NOT NULL, ADD tva_recuperable INT NOT NULL, ADD payement VARCHAR(255) NOT NULL, ADD site_adress VARCHAR(255) NOT NULL, ADD actif INT NOT NULL, DROP master_id, DROP number_vat, DROP recoverable_vat, DROP payment, DROP web_site_address, DROP asset, CHANGE dir_invoices dir_factures VARCHAR(255) DEFAULT NULL, CHANGE vatrate taux_tva DOUBLE PRECISION NOT NULL');
    }
}
