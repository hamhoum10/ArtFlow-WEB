<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329213908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_panier INT NOT NULL, prenom VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, numero INT NOT NULL, status VARCHAR(255) NOT NULL, total_amount VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', codepostal INT NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, id_commende_id INT DEFAULT NULL, name_produit VARCHAR(255) NOT NULL, artiste VARCHAR(255) NOT NULL, addres VARCHAR(255) NOT NULL, date_sort DATE NOT NULL, user_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_A60C9F1F6EB7C7B0 (id_commende_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE retour (id INT AUTO_INCREMENT NOT NULL, id_commende_id INT DEFAULT NULL, name_produit VARCHAR(255) NOT NULL, artiste VARCHAR(255) NOT NULL, addres VARCHAR(255) NOT NULL, date DATE NOT NULL, user_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_ED6FD3216EB7C7B0 (id_commende_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, id_commende_id INT DEFAULT NULL, name_produit VARCHAR(255) NOT NULL, artiste VARCHAR(255) NOT NULL, addres VARCHAR(255) NOT NULL, user_name VARCHAR(255) NOT NULL, date_entr DATE NOT NULL, UNIQUE INDEX UNIQ_4B3656606EB7C7B0 (id_commende_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT FK_A60C9F1F6EB7C7B0 FOREIGN KEY (id_commende_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE retour ADD CONSTRAINT FK_ED6FD3216EB7C7B0 FOREIGN KEY (id_commende_id) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656606EB7C7B0 FOREIGN KEY (id_commende_id) REFERENCES commande (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY FK_A60C9F1F6EB7C7B0');
        $this->addSql('ALTER TABLE retour DROP FOREIGN KEY FK_ED6FD3216EB7C7B0');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656606EB7C7B0');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE retour');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
