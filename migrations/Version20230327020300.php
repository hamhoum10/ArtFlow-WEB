<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230327020300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY usernameclientfk');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY id_panierFK');
        $this->addSql('ALTER TABLE evemt DROP FOREIGN KEY evemt_ibfk_1');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY id_articleFK');
        $this->addSql('ALTER TABLE ligne_panier DROP FOREIGN KEY id_panierfkoo');
        $this->addSql('ALTER TABLE livraison DROP FOREIGN KEY livraison_ibfk_1');
        $this->addSql('ALTER TABLE panier DROP FOREIGN KEY id_clientpanierFKk');
        $this->addSql('ALTER TABLE parler DROP FOREIGN KEY parler_ibfk_2');
        $this->addSql('ALTER TABLE parler DROP FOREIGN KEY parler_ibfk_1');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY fkclientttt');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY fkencheeee');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY rating_ibfk_1');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY rating_ibfk_2');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_2');
        $this->addSql('ALTER TABLE waiting DROP FOREIGN KEY waiting_ibfk_2');
        $this->addSql('ALTER TABLE waiting DROP FOREIGN KEY waiting_ibfk_3');
        $this->addSql('ALTER TABLE waiting DROP FOREIGN KEY waiting_ibfk_1');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE artisteee');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE enchere');
        $this->addSql('DROP TABLE evemt');
        $this->addSql('DROP TABLE ligne_panier');
        $this->addSql('DROP TABLE livraison');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE parler');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE promocode');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE retour');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE waiting');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY article_ibfk_2');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY article_ibfk_1');
        $this->addSql('DROP INDEX id_categorie ON article');
        $this->addSql('DROP INDEX id_artiste ON article');
        $this->addSql('ALTER TABLE article ADD idArtiste VARCHAR(255) DEFAULT NULL, DROP id_artiste, CHANGE description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E668CBE5EBD FOREIGN KEY (idArtiste) REFERENCES artiste (username)');
        $this->addSql('CREATE INDEX IDX_23A0E668CBE5EBD ON article (idArtiste)');
        $this->addSql('DROP INDEX usernameArtistefk ON artiste');
        $this->addSql('ALTER TABLE artiste CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE birthplace birthplace VARCHAR(255) NOT NULL, CHANGE birthdate birthdate VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE image image VARCHAR(255) NOT NULL, CHANGE address address VARCHAR(255) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(255) NOT NULL, CHANGE email email VARCHAR(255) NOT NULL, CHANGE username username VARCHAR(255) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE categorie CHANGE description description VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, lastname VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, phoneNumber VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, username VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, password VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE artisteee (id_artiste INT AUTO_INCREMENT NOT NULL, nom_artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prenom_artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(id_artiste)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, firstname VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, lastname VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, address VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, phonenumber VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, password VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX usernameclientfk (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE commande (id INT AUTO_INCREMENT NOT NULL, id_panier INT NOT NULL, prenom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, numero INT NOT NULL, status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, total_amount DOUBLE PRECISION NOT NULL, created_at DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, codepostal INT NOT NULL, adresse VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, les_articles VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX id_panierFK (id_panier), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE enchere (ide INT AUTO_INCREMENT NOT NULL, titre VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, prixdepart DOUBLE PRECISION NOT NULL, date_limite DATE NOT NULL, image VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY(ide)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE evemt (id INT AUTO_INCREMENT NOT NULL, id_art INT NOT NULL, date_evemt DATE DEFAULT NULL, description VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, finish_hour VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, start_hour VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, location VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, capacity VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, image VARCHAR(250) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, name VARCHAR(200) CHARACTER SET utf8 NOT NULL COLLATE `utf8_general_ci`, prix DOUBLE PRECISION NOT NULL, INDEX id_art (id_art), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ligne_panier (id INT AUTO_INCREMENT NOT NULL, id_panier INT NOT NULL, id_article INT NOT NULL, prix_unitaire DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, Nom_article VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, description VARCHAR(1000) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Nom_artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, Prenom_artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX id_panierfkoo (id_panier), INDEX id_articleFK (id_article), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE livraison (id INT AUTO_INCREMENT NOT NULL, id_commende INT NOT NULL, name_produit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, addres VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_sort DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX commende_fk (id_commende), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE panier (id_panier INT AUTO_INCREMENT NOT NULL, id_client INT NOT NULL, INDEX id_clientpanierFKk (id_client), PRIMARY KEY(id_panier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE parler (id_comment INT AUTO_INCREMENT NOT NULL, id_client INT NOT NULL, id_evemt INT NOT NULL, commentaire VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX id_evemt (id_evemt), INDEX id_client (id_client), PRIMARY KEY(id_comment)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE participant (idp INT AUTO_INCREMENT NOT NULL, id INT NOT NULL, ide INT NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX fkencheeee (ide), INDEX fkclientttt (id), PRIMARY KEY(idp)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE promocode (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX code (code), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rating (id_rating INT AUTO_INCREMENT NOT NULL, id_article INT NOT NULL, id_rater INT NOT NULL, rate DOUBLE PRECISION NOT NULL, INDEX id_article (id_article), INDEX id_rater (id_rater), PRIMARY KEY(id_rating)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id_res INT AUTO_INCREMENT NOT NULL, id_client INT NOT NULL, id_event INT NOT NULL, nb_place INT NOT NULL, dateres DATE DEFAULT NULL, INDEX id_client (id_client), INDEX id_event (id_event), PRIMARY KEY(id_res)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE retour (id INT AUTO_INCREMENT NOT NULL, name_produit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, addres VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, id_commende INT NOT NULL, user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX commendee_fk (id_commende), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, name_produit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, addres VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, id_commende INT NOT NULL, user_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, date_entr DATE DEFAULT \'CURRENT_TIMESTAMP\' NOT NULL, INDEX commendeee_fk (id_commende), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX username (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE waiting (id_waiting INT AUTO_INCREMENT NOT NULL, id_commande INT NOT NULL, user_name INT NOT NULL, id_ligne_panier INT NOT NULL, nom_produit VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, artiste VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, addres VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, INDEX id_commande (id_commande), INDEX user_name (user_name), INDEX id_ligne_panier (id_ligne_panier), PRIMARY KEY(id_waiting)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT usernameclientfk FOREIGN KEY (username) REFERENCES user (username)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT id_panierFK FOREIGN KEY (id_panier) REFERENCES panier (id_panier) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evemt ADD CONSTRAINT evemt_ibfk_1 FOREIGN KEY (id_art) REFERENCES artiste (id_artiste)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT id_articleFK FOREIGN KEY (id_article) REFERENCES article (id_article)');
        $this->addSql('ALTER TABLE ligne_panier ADD CONSTRAINT id_panierfkoo FOREIGN KEY (id_panier) REFERENCES panier (id_panier)');
        $this->addSql('ALTER TABLE livraison ADD CONSTRAINT livraison_ibfk_1 FOREIGN KEY (id_commende) REFERENCES commande (id)');
        $this->addSql('ALTER TABLE panier ADD CONSTRAINT id_clientpanierFKk FOREIGN KEY (id_client) REFERENCES client (id)');
        $this->addSql('ALTER TABLE parler ADD CONSTRAINT parler_ibfk_2 FOREIGN KEY (id_client) REFERENCES client (id)');
        $this->addSql('ALTER TABLE parler ADD CONSTRAINT parler_ibfk_1 FOREIGN KEY (id_evemt) REFERENCES evemt (id)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fkclientttt FOREIGN KEY (id) REFERENCES client (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT fkencheeee FOREIGN KEY (ide) REFERENCES enchere (ide) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT rating_ibfk_1 FOREIGN KEY (id_article) REFERENCES article (id_article) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT rating_ibfk_2 FOREIGN KEY (id_rater) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (id_client) REFERENCES client (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_2 FOREIGN KEY (id_event) REFERENCES evemt (id)');
        $this->addSql('ALTER TABLE waiting ADD CONSTRAINT waiting_ibfk_2 FOREIGN KEY (user_name) REFERENCES panier (id_client)');
        $this->addSql('ALTER TABLE waiting ADD CONSTRAINT waiting_ibfk_3 FOREIGN KEY (id_ligne_panier) REFERENCES ligne_panier (id)');
        $this->addSql('ALTER TABLE waiting ADD CONSTRAINT waiting_ibfk_1 FOREIGN KEY (id_commande) REFERENCES commande (id)');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E668CBE5EBD');
        $this->addSql('DROP INDEX IDX_23A0E668CBE5EBD ON article');
        $this->addSql('ALTER TABLE article ADD id_artiste VARCHAR(255) NOT NULL, DROP idArtiste, CHANGE description description VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT article_ibfk_2 FOREIGN KEY (id_artiste) REFERENCES artiste (username)');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT article_ibfk_1 FOREIGN KEY (id_categorie) REFERENCES categorie (id_categorie)');
        $this->addSql('CREATE INDEX id_categorie ON article (id_categorie)');
        $this->addSql('CREATE INDEX id_artiste ON article (id_artiste)');
        $this->addSql('ALTER TABLE artiste CHANGE firstname firstname VARCHAR(200) NOT NULL, CHANGE lastname lastname VARCHAR(200) NOT NULL, CHANGE birthplace birthplace VARCHAR(200) NOT NULL, CHANGE birthdate birthdate VARCHAR(200) NOT NULL, CHANGE description description VARCHAR(200) NOT NULL, CHANGE image image VARCHAR(200) NOT NULL, CHANGE address address VARCHAR(200) NOT NULL, CHANGE phonenumber phonenumber VARCHAR(200) NOT NULL, CHANGE email email VARCHAR(200) NOT NULL, CHANGE username username VARCHAR(200) NOT NULL, CHANGE password password VARCHAR(200) NOT NULL');
        $this->addSql('CREATE INDEX usernameArtistefk ON artiste (username)');
        $this->addSql('ALTER TABLE categorie CHANGE description description VARCHAR(1000) NOT NULL');
    }
}
