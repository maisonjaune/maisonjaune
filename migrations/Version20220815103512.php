<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220815103512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE node_category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, shortname VARCHAR(10) NOT NULL, slug VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE node (id INT AUTO_INCREMENT NOT NULL, node_type VARCHAR(255) NOT NULL, author_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_actif TINYINT(1) NOT NULL, is_draft TINYINT(1) NOT NULL, is_sticky TINYINT(1) NOT NULL, is_commentable TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_857FE845F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE node_brief (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE node_page (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE node_post (id INT NOT NULL, excerpt LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, civility VARCHAR(255) DEFAULT NULL, firstname VARCHAR(150) DEFAULT NULL, lastname VARCHAR(150) DEFAULT NULL, email VARCHAR(150) NOT NULL, is_actif TINYINT(1) NOT NULL, is_member TINYINT(1) NOT NULL, last_connection_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', password VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE node ADD CONSTRAINT FK_857FE845F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE node_brief ADD CONSTRAINT FK_61082FBBF396750 FOREIGN KEY (id) REFERENCES node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE node_page ADD CONSTRAINT FK_D3AE3BB6BF396750 FOREIGN KEY (id) REFERENCES node (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE node_post ADD CONSTRAINT FK_9D2EE11BBF396750 FOREIGN KEY (id) REFERENCES node (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE node DROP FOREIGN KEY FK_857FE845F675F31B');
        $this->addSql('ALTER TABLE node_brief DROP FOREIGN KEY FK_61082FBBF396750');
        $this->addSql('ALTER TABLE node_page DROP FOREIGN KEY FK_D3AE3BB6BF396750');
        $this->addSql('ALTER TABLE node_post DROP FOREIGN KEY FK_9D2EE11BBF396750');
        $this->addSql('DROP TABLE node_category');
        $this->addSql('DROP TABLE node');
        $this->addSql('DROP TABLE node_brief');
        $this->addSql('DROP TABLE node_page');
        $this->addSql('DROP TABLE node_post');
        $this->addSql('DROP TABLE user');
    }
}
