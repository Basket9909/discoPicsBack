<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220811085016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favorite_publication_publication DROP FOREIGN KEY FK_3AAC7C2734D9EEF5');
        $this->addSql('ALTER TABLE favorite_publication_publication DROP FOREIGN KEY FK_3AAC7C2738B217A7');
        $this->addSql('ALTER TABLE favorite_publication_users DROP FOREIGN KEY FK_817BFB9367B3B43D');
        $this->addSql('ALTER TABLE favorite_publication_users DROP FOREIGN KEY FK_817BFB9334D9EEF5');
        $this->addSql('ALTER TABLE favorite_user_publication DROP FOREIGN KEY FK_4ACBD7E938B217A7');
        $this->addSql('ALTER TABLE favorite_user_publication DROP FOREIGN KEY FK_4ACBD7E9FA3A7DFB');
        $this->addSql('ALTER TABLE favorite_user_users DROP FOREIGN KEY FK_E88ABC4C67B3B43D');
        $this->addSql('ALTER TABLE favorite_user_users DROP FOREIGN KEY FK_E88ABC4CFA3A7DFB');
        $this->addSql('DROP TABLE favorite_publication');
        $this->addSql('DROP TABLE favorite_publication_publication');
        $this->addSql('DROP TABLE favorite_publication_users');
        $this->addSql('DROP TABLE favorite_user');
        $this->addSql('DROP TABLE favorite_user_publication');
        $this->addSql('DROP TABLE favorite_user_users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_publication (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorite_publication_publication (favorite_publication_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_3AAC7C2738B217A7 (publication_id), INDEX IDX_3AAC7C2734D9EEF5 (favorite_publication_id), PRIMARY KEY(favorite_publication_id, publication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorite_publication_users (favorite_publication_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_817BFB9367B3B43D (users_id), INDEX IDX_817BFB9334D9EEF5 (favorite_publication_id), PRIMARY KEY(favorite_publication_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorite_user (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorite_user_publication (favorite_user_id INT NOT NULL, publication_id INT NOT NULL, INDEX IDX_4ACBD7E9FA3A7DFB (favorite_user_id), INDEX IDX_4ACBD7E938B217A7 (publication_id), PRIMARY KEY(favorite_user_id, publication_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE favorite_user_users (favorite_user_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_E88ABC4CFA3A7DFB (favorite_user_id), INDEX IDX_E88ABC4C67B3B43D (users_id), PRIMARY KEY(favorite_user_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE favorite_publication_publication ADD CONSTRAINT FK_3AAC7C2734D9EEF5 FOREIGN KEY (favorite_publication_id) REFERENCES favorite_publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_publication_publication ADD CONSTRAINT FK_3AAC7C2738B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_publication_users ADD CONSTRAINT FK_817BFB9367B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_publication_users ADD CONSTRAINT FK_817BFB9334D9EEF5 FOREIGN KEY (favorite_publication_id) REFERENCES favorite_publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_publication ADD CONSTRAINT FK_4ACBD7E938B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_publication ADD CONSTRAINT FK_4ACBD7E9FA3A7DFB FOREIGN KEY (favorite_user_id) REFERENCES favorite_user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_users ADD CONSTRAINT FK_E88ABC4C67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favorite_user_users ADD CONSTRAINT FK_E88ABC4CFA3A7DFB FOREIGN KEY (favorite_user_id) REFERENCES favorite_user (id) ON DELETE CASCADE');
    }
}
