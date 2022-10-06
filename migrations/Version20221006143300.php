<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221006143300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE publication_users (publication_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_F15DE36538B217A7 (publication_id), INDEX IDX_F15DE36567B3B43D (users_id), PRIMARY KEY(publication_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE publication_users ADD CONSTRAINT FK_F15DE36538B217A7 FOREIGN KEY (publication_id) REFERENCES publication (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE publication_users ADD CONSTRAINT FK_F15DE36567B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE publication_users DROP FOREIGN KEY FK_F15DE36538B217A7');
        $this->addSql('ALTER TABLE publication_users DROP FOREIGN KEY FK_F15DE36567B3B43D');
        $this->addSql('DROP TABLE publication_users');
    }
}
