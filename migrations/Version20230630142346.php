<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230630142346 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE taoption_provider (id INT AUTO_INCREMENT NOT NULL, id_provider INT NOT NULL, id_option INT NOT NULL, opt_id_source VARCHAR(255) NOT NULL, description_source VARCHAR(255) NOT NULL, id_product INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE taproduct_option (id INT AUTO_INCREMENT NOT NULL, id_product INT NOT NULL, id_option INT NOT NULL, libelle VARCHAR(255) NOT NULL, is_actif INT NOT NULL, default_value VARCHAR(255) NOT NULL, option_min_value VARCHAR(255) NOT NULL, option_max_value VARCHAR(255) NOT NULL, id_host VARCHAR(255) NOT NULL, date_hour_last_seen DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tproduct_host (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, satellite_id_parent INT NOT NULL, is_actif VARCHAR(255) NOT NULL, informations VARCHAR(255) NOT NULL, url_picto_file VARCHAR(255) NOT NULL, rattachement INT NOT NULL, libelle_url VARCHAR(255) NOT NULL, product_host_order INT NOT NULL, title VARCHAR(255) NOT NULL, subtitle VARCHAR(255) NOT NULL, description1 VARCHAR(255) NOT NULL, description2 VARCHAR(255) NOT NULL, description3 VARCHAR(255) NOT NULL, meta_title VARCHAR(255) NOT NULL, meta_description2 VARCHAR(255) NOT NULL, min_price VARCHAR(255) NOT NULL, id_product INT NOT NULL, id_host INT NOT NULL, footer_link VARCHAR(255) NOT NULL, last_update VARCHAR(255) NOT NULL, list_last_update VARCHAR(255) NOT NULL, show_on_home_bottom INT NOT NULL, show_on_home_top INT NOT NULL, variant INT NOT NULL, count_satellite INT NOT NULL, date_time_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_time_list_last_update DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE taoption_provider');
        $this->addSql('DROP TABLE taproduct_option');
        $this->addSql('DROP TABLE tproduct_host');
    }
}
