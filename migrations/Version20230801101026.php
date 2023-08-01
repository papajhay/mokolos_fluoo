<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230801101026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hosts (id INT AUTO_INCREMENT NOT NULL, mail_ns VARCHAR(255) NOT NULL, adresse_www VARCHAR(255) NOT NULL, tel_std VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, billing_name VARCHAR(255) NOT NULL, billing_address VARCHAR(255) NOT NULL, billing_address2 VARCHAR(255) NOT NULL, cp_billing VARCHAR(255) NOT NULL, city_billing VARCHAR(255) NOT NULL, country_billing VARCHAR(255) NOT NULL, country VARCHAR(255) NOT NULL, country_code VARCHAR(255) NOT NULL, id_host_type INT NOT NULL, master INT NOT NULL, vat DOUBLE PRECISION NOT NULL, mail_info VARCHAR(255) NOT NULL, domain VARCHAR(255) NOT NULL, fax VARCHAR(255) NOT NULL, id_four_p24 INT NOT NULL, local_currencies_id INT NOT NULL, google_analytics_id INT NOT NULL, google_ad_words_id INT NOT NULL, google_id_conversion VARCHAR(255) NOT NULL, google_ad_words_remarketing_id VARCHAR(255) NOT NULL, bing_ads_id INT NOT NULL, language_code VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, order_host INT NOT NULL, data VARCHAR(255) NOT NULL, product_host VARCHAR(255) NOT NULL, facebook_url VARCHAR(255) NOT NULL, facebook_app_id INT NOT NULL, credit_name VARCHAR(255) NOT NULL, reference_language VARCHAR(255) NOT NULL, notice_rating DOUBLE PRECISION NOT NULL, opinion_number INT NOT NULL, secretkey INT NOT NULL, hos_site_id INT NOT NULL, amount_premuim DOUBLE PRECISION NOT NULL, product_number INT NOT NULL, hos_delay INT NOT NULL, price_decimal DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tmessage (id INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, field_label VARCHAR(255) NOT NULL, field_type VARCHAR(255) NOT NULL, field_value VARCHAR(255) NOT NULL, field_is_mandatory TINYINT(1) NOT NULL, field_size_min INT NOT NULL, field_size_max INT NOT NULL, field_has_error TINYINT(1) NOT NULL, error_field VARCHAR(255) NOT NULL, error_char_min INT NOT NULL, error_char_max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE taproduct_option_value_provider DROP id_provider, DROP id_option_value, DROP id_product');
        $this->addSql('ALTER TABLE ttxt ADD id_product_host INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE hosts');
        $this->addSql('DROP TABLE tmessage');
        $this->addSql('ALTER TABLE taproduct_option_value_provider ADD id_provider INT NOT NULL, ADD id_option_value INT NOT NULL, ADD id_product INT NOT NULL');
        $this->addSql('ALTER TABLE ttxt DROP id_product_host');
    }
}
