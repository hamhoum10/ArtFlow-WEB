<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230501123924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_panier INT DEFAULT NULL, prenom VARCHAR(150) NOT NULL, nom VARCHAR(150) NOT NULL, numero INT NOT NULL, status VARCHAR(150) NOT NULL, total_amount DOUBLE PRECISION NOT NULL, created_at VARCHAR(255) NOT NULL, codepostal INT NOT NULL, adresse VARCHAR(150) NOT NULL, INDEX IDX_6EEAA67D2FBB81F (id_panier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ligne_panier (id INT AUTO_INCREMENT NOT NULL, id_article INT DEFAULT NULL, id_panier INT DEFAULT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, nom_article VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, nom_artiste VARCHAR(255) NOT NULL, prenom_artiste VARCHAR(255) NOT NULL, INDEX IDX_21691B4DCA7A716 (id_article), INDEX IDX_21691B42FBB81F (id_panier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id_panier INT AUTO_INCREMENT NOT NULL, id_client INT DEFAULT NULL, UNIQUE INDEX UNIQ_24CC0DF2E173B1B8 (id_client), PRIMARY KEY(id_panier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promocode (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D2FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B4DCA7A716 FOREIGN KEY (id_article) REFERENCES article (id_article)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT FK_21691B42FBB81F FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT FK_24CC0DF2E173B1B8 FOREIGN KEY (id_client) REFERENCES client (id)');
        $this->addSql('ALTER TABLE user CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE email email VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D2FBB81F');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B4DCA7A716');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY FK_21691B42FBB81F');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY FK_24CC0DF2E173B1B8');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE promocode');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user CHANGE id id INT NOT NULL, CHANGE email email VARCHAR(255) NOT NULL');
    }
}
