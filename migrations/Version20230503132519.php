<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230503132519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, id_commende_id INT DEFAULT NULL, name_produit VARCHAR(255) NOT NULL, artiste VARCHAR(255) NOT NULL, addres VARCHAR(255) NOT NULL, date_sort DATE NOT NULL, user_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A60C9F1F6EB7C7B0 (id_commende_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour (id INT AUTO_INCREMENT NOT NULL, id_commende_id INT DEFAULT NULL, name_produit VARCHAR(255) NOT NULL, artiste VARCHAR(255) NOT NULL, addres VARCHAR(255) NOT NULL, date DATE NOT NULL, user_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_ED6FD3216EB7C7B0 (id_commende_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, id_commende_id INT DEFAULT NULL, name_produit VARCHAR(255) NOT NULL, artiste VARCHAR(255) NOT NULL, addres VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, date_entr DATE NOT NULL, UNIQUE INDEX UNIQ_4B3656606EB7C7B0 (id_commende_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F6EB7C7B0 FOREIGN KEY (id_commende_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE retour ADD CONSTRAINT FK_ED6FD3216EB7C7B0 FOREIGN KEY (id_commende_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656606EB7C7B0 FOREIGN KEY (id_commende_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F6EB7C7B0');
        $this->addSql('ALTER TABLE retour DROP FOREIGN KEY FK_ED6FD3216EB7C7B0');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656606EB7C7B0');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE retour');
        $this->addSql('DROP TABLE stock');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP INDEX UNIQ_8D93D649F85E0677 ON user');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(255) NOT NULL');
    }
}
