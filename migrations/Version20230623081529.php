<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623081529 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, currencies_id INT NOT NULL, master_fournisseur_id INT NOT NULL, number_tva INT NOT NULL, dir_factures VARCHAR(255) DEFAULT NULL, access_login VARCHAR(255) DEFAULT NULL, comment VARCHAR(255) NOT NULL, taux_tva DOUBLE PRECISION NOT NULL, tva_recuperable INT NOT NULL, billing_company INT NOT NULL, billing_street_adress INT NOT NULL, billing_post_code INT NOT NULL, billing_city INT NOT NULL, billing_name INT NOT NULL, billing_telephone INT NOT NULL, salutation INT NOT NULL, payement VARCHAR(255) NOT NULL, order_selection INT NOT NULL, site_adress VARCHAR(255) NOT NULL, site_login VARCHAR(255) NOT NULL, site_pass VARCHAR(255) NOT NULL, customer_id VARCHAR(255) DEFAULT NULL, party_id VARCHAR(255) DEFAULT NULL, country_code VARCHAR(255) NOT NULL, actif INT NOT NULL, date_valid_cache_price DATETIME NOT NULL, white_label_delivery INT NOT NULL, id_host_for_selection VARCHAR(255) DEFAULT NULL, error_code INT NOT NULL, date_hour_valid_cache_price DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE fournisseur');
    }
}
