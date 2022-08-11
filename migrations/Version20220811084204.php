<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220811084204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE coments (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, publication_id INT NOT NULL, date DATE NOT NULL, comment LONGTEXT NOT NULL, INDEX IDX_73DCFA1AA76ED395 (user_id), INDEX IDX_73DCFA1A38B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_publication (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_publication_publication (favorite_publication_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_3AAC7C2734D9EEF5 (favorite_publication_id), INDEX IDX_3AAC7C2738B217A7 (publication_id), PRIMARY KEY(favorite_publication_id, publication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_publication_users (favorite_publication_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_817BFB9334D9EEF5 (favorite_publication_id), INDEX IDX_817BFB9367B3B43D (users_id), PRIMARY KEY(favorite_publication_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_user_users (favorite_user_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_E88ABC4CFA3A7DFB (favorite_user_id), INDEX IDX_E88ABC4C67B3B43D (users_id), PRIMARY KEY(favorite_user_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favorite_user_publication (favorite_user_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_4ACBD7E9FA3A7DFB (favorite_user_id), INDEX IDX_4ACBD7E938B217A7 (publication_id), PRIMARY KEY(favorite_user_id, publication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publication (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(50) DEFAULT NULL, country VARCHAR(50) DEFAULT NULL, adress VARCHAR(255) DEFAULT NULL, details LONGTEXT DEFAULT NULL, tips LONGTEXT DEFAULT NULL, INDEX IDX_AF3C6779A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_D8892622A76ED395 (user_id), INDEX IDX_D889262238B217A7 (publication_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, mail VARCHAR(100) NOT NULL, password VARCHAR(50) NOT NULL, bird DATE NOT NULL, insta_link VARCHAR(255) DEFAULT NULL, picture VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, role JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coments ADD CONSTRAINT FK_73DCFA1AA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE coments ADD CONSTRAINT FK_73DCFA1A38B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
        $this->addSql('ALTER TABLE favorite_publication_publication ADD CONSTRAINT FK_3AAC7C2734D9EEF5 FOREIGN KEY (favorite_publication_id) REFERENCES favorite_publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_publication_publication ADD CONSTRAINT FK_3AAC7C2738B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_publication_users ADD CONSTRAINT FK_817BFB9334D9EEF5 FOREIGN KEY (favorite_publication_id) REFERENCES favorite_publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_publication_users ADD CONSTRAINT FK_817BFB9367B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_users ADD CONSTRAINT FK_E88ABC4CFA3A7DFB FOREIGN KEY (favorite_user_id) REFERENCES favorite_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_users ADD CONSTRAINT FK_E88ABC4C67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_publication ADD CONSTRAINT FK_4ACBD7E9FA3A7DFB FOREIGN KEY (favorite_user_id) REFERENCES favorite_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_publication ADD CONSTRAINT FK_4ACBD7E938B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication ADD CONSTRAINT FK_AF3C6779A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262238B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coments DROP FOREIGN KEY FK_73DCFA1AA76ED395');
        $this->addSql('ALTER TABLE coments DROP FOREIGN KEY FK_73DCFA1A38B217A7');
        $this->addSql('ALTER TABLE favorite_publication_publication DROP FOREIGN KEY FK_3AAC7C2734D9EEF5');
        $this->addSql('ALTER TABLE favorite_publication_publication DROP FOREIGN KEY FK_3AAC7C2738B217A7');
        $this->addSql('ALTER TABLE favorite_publication_users DROP FOREIGN KEY FK_817BFB9334D9EEF5');
        $this->addSql('ALTER TABLE favorite_publication_users DROP FOREIGN KEY FK_817BFB9367B3B43D');
        $this->addSql('ALTER TABLE favorite_user_users DROP FOREIGN KEY FK_E88ABC4CFA3A7DFB');
        $this->addSql('ALTER TABLE favorite_user_users DROP FOREIGN KEY FK_E88ABC4C67B3B43D');
        $this->addSql('ALTER TABLE favorite_user_publication DROP FOREIGN KEY FK_4ACBD7E9FA3A7DFB');
        $this->addSql('ALTER TABLE favorite_user_publication DROP FOREIGN KEY FK_4ACBD7E938B217A7');
        $this->addSql('ALTER TABLE publication DROP FOREIGN KEY FK_AF3C6779A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262238B217A7');
        $this->addSql('DROP TABLE coments');
        $this->addSql('DROP TABLE favorite_publication');
        $this->addSql('DROP TABLE favorite_publication_publication');
        $this->addSql('DROP TABLE favorite_publication_users');
        $this->addSql('DROP TABLE favorite_user');
        $this->addSql('DROP TABLE favorite_user_users');
        $this->addSql('DROP TABLE favorite_user_publication');
        $this->addSql('DROP TABLE publication');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
