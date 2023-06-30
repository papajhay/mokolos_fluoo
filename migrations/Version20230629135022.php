<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230629135022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE provider (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, currencies_id INT NOT NULL, master_fournisseur_id INT NOT NULL, number_tva INT NOT NULL, dir_factures VARCHAR(255) DEFAULT NULL, access_login VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) NOT NULL, taux_tva DOUBLE PRECISION NOT NULL, tva_recuperable INT NOT NULL, billing_company INT NOT NULL, billing_street_adress INT NOT NULL, billing_post_code INT NOT NULL, billing_city INT NOT NULL, billing_name INT NOT NULL, billing_telephone INT NOT NULL, salutation INT NOT NULL, payement VARCHAR(255) NOT NULL, order_selection INT NOT NULL, site_adress VARCHAR(255) NOT NULL, site_login VARCHAR(255) NOT NULL, site_pass VARCHAR(255) NOT NULL, customer_id VARCHAR(255) DEFAULT NULL, party_id VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) NOT NULL, actif INT NOT NULL, date_valid_cache_price DATETIME NOT NULL, white_label_delivery INT NOT NULL, id_host_for_selection VARCHAR(255) DEFAULT NULL, error_code INT NOT NULL, date_hour_valid_cache_price DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tproduct_host (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, satellite_id_parent INT NOT NULL, is_actif VARCHAR(255) NOT NULL, informations VARCHAR(255) NOT NULL, url_picto_file VARCHAR(255) NOT NULL, rattachement INT NOT NULL, libelle_url VARCHAR(255) NOT NULL, product_host_order INT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, description1 VARCHAR(255) NOT NULL, description2 VARCHAR(255) NOT NULL, description3 VARCHAR(255) NOT NULL, meta_title VARCHAR(255) NOT NULL, meta_description2 VARCHAR(255) NOT NULL, min_price VARCHAR(255) NOT NULL, id_product INT NOT NULL, id_host INT NOT NULL, footer_link VARCHAR(255) NOT NULL, last_update VARCHAR(255) NOT NULL, list_last_update VARCHAR(255) NOT NULL, show_on_home_bottom INT NOT NULL, show_on_home_top INT NOT NULL, variant INT NOT NULL, count_satellite INT NOT NULL, date_time_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_time_list_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE provider');
        $this->addSql('DROP TABLE tproduct_host');
    }
}
