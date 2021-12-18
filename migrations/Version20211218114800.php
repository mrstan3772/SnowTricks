<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211218114800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, comment_author_id INT NOT NULL, comment_trick_id INT NOT NULL, comment_content VARCHAR(5000) NOT NULL, comment_creation_date DATETIME NOT NULL, INDEX IDX_9474526C1F0B124D (comment_author_id), INDEX IDX_9474526CAFC5DD28 (comment_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `group` (id INT AUTO_INCREMENT NOT NULL, group_name VARCHAR(255) NOT NULL, group_creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick (id INT AUTO_INCREMENT NOT NULL, trick_author_id INT NOT NULL, trick_group_id INT NOT NULL, trick_name VARCHAR(255) NOT NULL, trick_description VARCHAR(10000) NOT NULL, trick_creation_date DATETIME NOT NULL, trick_update_date DATETIME NOT NULL, INDEX IDX_D8F0A91EADCE8E82 (trick_author_id), INDEX IDX_D8F0A91E9B875DF8 (trick_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trick_attachment (id INT AUTO_INCREMENT NOT NULL, ta_trick_id INT NOT NULL, ta_type VARCHAR(255) NOT NULL, ta_path VARCHAR(255) NOT NULL, INDEX IDX_F0D7492661111033 (ta_trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, user_name VARCHAR(255) NOT NULL, user_email VARCHAR(255) NOT NULL, user_avatar VARCHAR(255) NOT NULL, user_password VARCHAR(255) NOT NULL, user_token VARCHAR(255) NOT NULL, user_active TINYINT(1) NOT NULL, user_admin TINYINT(1) NOT NULL, user_registration_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C1F0B124D FOREIGN KEY (comment_author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CAFC5DD28 FOREIGN KEY (comment_trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91EADCE8E82 FOREIGN KEY (trick_author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE trick ADD CONSTRAINT FK_D8F0A91E9B875DF8 FOREIGN KEY (trick_group_id) REFERENCES `group` (id)');
        $this->addSql('ALTER TABLE trick_attachment ADD CONSTRAINT FK_F0D7492661111033 FOREIGN KEY (ta_trick_id) REFERENCES trick (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91E9B875DF8');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CAFC5DD28');
        $this->addSql('ALTER TABLE trick_attachment DROP FOREIGN KEY FK_F0D7492661111033');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C1F0B124D');
        $this->addSql('ALTER TABLE trick DROP FOREIGN KEY FK_D8F0A91EADCE8E82');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE `group`');
        $this->addSql('DROP TABLE trick');
        $this->addSql('DROP TABLE trick_attachment');
        $this->addSql('DROP TABLE user');
    }
}
