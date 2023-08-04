<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230724111032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE taproduct_option_value (id INT AUTO_INCREMENT NOT NULL, t_option_value_id INT DEFAULT NULL, t_aproduct_option_id INT DEFAULT NULL, id_product INT NOT NULL, id_option_value INT NOT NULL, is_actif INT NOT NULL, libelle VARCHAR(255) NOT NULL, product_option_value_order INT NOT NULL, id_host INT NOT NULL, date_last_seen DATETIME NOT NULL, localised LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', datetime_last_seen DATETIME NOT NULL, INDEX IDX_45B06EA896647EBE (t_option_value_id), INDEX IDX_45B06EA89B3D4EEE (t_aproduct_option_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE toption_value (id INT AUTO_INCREMENT NOT NULL, toption_id INT NOT NULL, libelle VARCHAR(255) NOT NULL, is_actif INT NOT NULL, INDEX IDX_2A7073A35DE54455 (toption_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE taproduct_option_value ADD CONSTRAINT FK_45B06EA896647EBE FOREIGN KEY (t_option_value_id) REFERENCES toption_value (id)');
        $this->addSql('ALTER TABLE taproduct_option_value ADD CONSTRAINT FK_45B06EA89B3D4EEE FOREIGN KEY (t_aproduct_option_id) REFERENCES taproduct_option (id)');
        $this->addSql('ALTER TABLE toption_value ADD CONSTRAINT FK_2A7073A35DE54455 FOREIGN KEY (toption_id) REFERENCES toption (id)');
        $this->addSql('ALTER TABLE taproduct_option ADD toption_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE taproduct_option ADD CONSTRAINT FK_F56812135DE54455 FOREIGN KEY (toption_id) REFERENCES toption (id)');
        $this->addSql('CREATE INDEX IDX_F56812135DE54455 ON taproduct_option (toption_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE taproduct_option_value DROP FOREIGN KEY FK_45B06EA896647EBE');
        $this->addSql('ALTER TABLE taproduct_option_value DROP FOREIGN KEY FK_45B06EA89B3D4EEE');
        $this->addSql('ALTER TABLE toption_value DROP FOREIGN KEY FK_2A7073A35DE54455');
        $this->addSql('DROP TABLE taproduct_option_value');
        $this->addSql('DROP TABLE toption_value');
        $this->addSql('ALTER TABLE taproduct_option DROP FOREIGN KEY FK_F56812135DE54455');
        $this->addSql('DROP INDEX IDX_F56812135DE54455 ON taproduct_option');
        $this->addSql('ALTER TABLE taproduct_option DROP toption_id');
    }
}
