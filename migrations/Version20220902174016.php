<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220902174016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ingredient (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6BAF7870A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receipt (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, duration BIGINT DEFAULT NULL, nb_persons INT DEFAULT NULL, difficulty INT NOT NULL, description VARCHAR(255) NOT NULL, price INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_favorite TINYINT(1) NOT NULL, is_public TINYINT(1) NOT NULL, INDEX IDX_5399B645A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE receipt_ingredient (receipt_id INT NOT NULL, ingredient_id INT NOT NULL, INDEX IDX_918CC69B2B5CA896 (receipt_id), INDEX IDX_918CC69B933FE08C (ingredient_id), PRIMARY KEY(receipt_id, ingredient_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, pseudo VARCHAR(50) DEFAULT NULL, email VARCHAR(180) NOT NULL, full_name VARCHAR(50) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ingredient ADD CONSTRAINT FK_6BAF7870A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE receipt ADD CONSTRAINT FK_5399B645A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE receipt_ingredient ADD CONSTRAINT FK_918CC69B2B5CA896 FOREIGN KEY (receipt_id) REFERENCES receipt (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE receipt_ingredient ADD CONSTRAINT FK_918CC69B933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient DROP FOREIGN KEY FK_6BAF7870A76ED395');
        $this->addSql('ALTER TABLE receipt DROP FOREIGN KEY FK_5399B645A76ED395');
        $this->addSql('ALTER TABLE receipt_ingredient DROP FOREIGN KEY FK_918CC69B2B5CA896');
        $this->addSql('ALTER TABLE receipt_ingredient DROP FOREIGN KEY FK_918CC69B933FE08C');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE receipt');
        $this->addSql('DROP TABLE receipt_ingredient');
        $this->addSql('DROP TABLE user');
    }
}
