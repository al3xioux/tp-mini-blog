<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Création des utilisateurs
        $users = [];
        
        // Utilisateur admin
        $admin = new User();
        $admin->setEmail('admin@blog.com');
        $admin->setFirstName('Admin');
        $admin->setLastName('Système');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setIsActive(true);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setCreatedAt(new \DateTimeImmutable('-6 months'));
        $manager->persist($admin);
        $users[] = $admin;

        // Utilisateurs réguliers
        $userNames = [
            ['Marie', 'Dubois', 'marie.dubois@example.com'],
            ['Pierre', 'Martin', 'pierre.martin@example.com'],
            ['Sophie', 'Bernard', 'sophie.bernard@example.com'],
            ['Thomas', 'Petit', 'thomas.petit@example.com'],
            ['Julie', 'Robert', 'julie.robert@example.com'],
            ['Lucas', 'Richard', 'lucas.richard@example.com'],
            ['Emma', 'Durand', 'emma.durand@example.com'],
            ['Alexandre', 'Moreau', 'alexandre.moreau@example.com'],
        ];

        foreach ($userNames as $index => $userData) {
            $user = new User();
            $user->setEmail($userData[2]);
            $user->setFirstName($userData[0]);
            $user->setLastName($userData[1]);
            $user->setRoles(['ROLE_USER']);
            $user->setIsActive($index < 7); // Un utilisateur inactif
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setCreatedAt(new \DateTimeImmutable('-' . rand(30, 180) . ' days'));
            $manager->persist($user);
            $users[] = $user;
        }

        // Création des catégories
        $categories = [];
        $categoryData = [
            ['Technologie', 'Articles sur les dernières innovations technologiques'],
            ['Programmation', 'Tutoriels et astuces de développement'],
            ['Design', 'Tendances et conseils en design web'],
            ['Business', 'Actualités et stratégies business'],
            ['Lifestyle', 'Mode de vie et bien-être'],
            ['Voyage', 'Récits et conseils de voyage'],
        ];

        foreach ($categoryData as $catData) {
            $category = new Category();
            $category->setName($catData[0]);
            $category->setDescription($catData[1]);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Création des posts
        $posts = [];
        $postData = [
            ['Introduction à Symfony 7', 'Découvrez les nouvelles fonctionnalités de Symfony 7 et comment les utiliser efficacement dans vos projets. Ce framework PHP continue d\'évoluer avec des améliorations de performance et de nouvelles fonctionnalités qui facilitent le développement d\'applications web modernes.'],
            ['Les bases de PHP 8.2', 'PHP 8.2 apporte de nombreuses améliorations. Explorons les nouvelles fonctionnalités ensemble et voyons comment elles peuvent améliorer votre code et votre productivité.'],
            ['Design Patterns en pratique', 'Les design patterns sont essentiels pour créer du code maintenable. Apprenez à les utiliser correctement dans vos projets quotidiens et améliorez la qualité de votre code.'],
            ['Guide du télétravail efficace', 'Comment optimiser sa productivité en travaillant depuis chez soi ? Découvrez nos meilleurs conseils pour rester motivé et organisé.'],
            ['Docker pour les débutants', 'Comprendre les conteneurs et comment Docker peut simplifier votre workflow de développement. Un guide complet pour démarrer avec cette technologie incontournable.'],
            ['REST API Best Practices', 'Les bonnes pratiques pour concevoir et implémenter des API REST robustes et maintenables. Apprenez à créer des API professionnelles.'],
            ['CSS Grid vs Flexbox', 'Quand utiliser Grid ou Flexbox ? Un comparatif détaillé pour faire le bon choix selon vos besoins de mise en page.'],
            ['Sécurité Web en 2026', 'Les principales vulnérabilités à éviter et comment protéger vos applications web contre les attaques courantes.'],
            ['Git Flow expliqué', 'Maîtrisez le workflow Git pour une collaboration efficace en équipe. Branches, merge, rebase... tout ce qu\'il faut savoir.'],
            ['L\'art du Clean Code', 'Écrire du code lisible et maintenable : principes et pratiques essentielles pour tout développeur professionnel.'],
            ['Découvrir le Japon', 'Guide complet pour un premier voyage au Japon : culture, nourriture, transports et lieux incontournables.'],
            ['Méditation et productivité', 'Comment la méditation peut améliorer votre concentration et votre efficacité au travail au quotidien.'],
            ['Freelancing : débuter sereinement', 'Tous les conseils pour lancer son activité de freelance avec succès et éviter les pièges classiques.'],
            ['TypeScript en 2026', 'Les fonctionnalités avancées de TypeScript et pourquoi il est devenu incontournable dans le développement web moderne.'],
            ['UX Design : les fondamentaux', 'Les principes de base pour créer des interfaces utilisateur intuitives et agréables qui raviront vos utilisateurs.'],
            ['Microservices avec Symfony', 'Architecture en microservices : comment l\'implémenter avec Symfony et quels sont les avantages et inconvénients.'],
            ['Marketing digital pour développeurs', 'Promouvoir vos projets et services : stratégies marketing adaptées aux développeurs et créateurs de contenu.'],
            ['Les bases de React', 'Introduction complète à React : composants, hooks, state management et écosystème moderne.'],
            ['Photographie de voyage', 'Conseils pratiques pour capturer de magnifiques souvenirs lors de vos aventures autour du monde.'],
            ['Cuisine healthy et rapide', 'Recettes équilibrées à préparer en moins de 30 minutes pour une vie saine et active.'],
        ];

        foreach ($postData as $index => $pData) {
            $post = new Post();
            $post->setTitle($pData[0]);
            $post->setContent($pData[1]);
            $post->setAuthor($users[array_rand($users)]);
            $post->setCategory($categories[array_rand($categories)]);
            $post->setPicture('post-' . ($index + 1) . '.jpg');
            $post->setPublishedAt(new \DateTime('-' . rand(1, 90) . ' days'));
            $manager->persist($post);
            $posts[] = $post;
        }

        // Création des commentaires
        $commentTexts = [
            'Excellent article, très instructif !',
            'Merci pour ce partage, ça m\'aide beaucoup.',
            'Je ne suis pas d\'accord avec certains points.',
            'Très bien expliqué, continuez comme ça !',
            'Est-ce que vous avez d\'autres ressources à recommander ?',
            'J\'ai appris quelque chose aujourd\'hui, merci !',
            'Article de qualité, bien documenté.',
            'Intéressant mais un peu trop technique pour moi.',
            'Parfait, exactement ce que je cherchais !',
            'Pourriez-vous développer le dernier point ?',
            'Super contenu, j\'attends la suite avec impatience.',
            'Cela m\'a donné de nouvelles idées, merci !',
            'Je vais tester ça dès demain.',
            'Très pertinent dans le contexte actuel.',
            'Bravo pour la clarté de l\'explication !',
        ];

        $commentStatuses = ['approved', 'approved', 'approved', 'pending', 'rejected'];

        foreach ($posts as $post) {
            // Entre 2 et 8 commentaires par post
            $numComments = rand(2, 8);
            for ($i = 0; $i < $numComments; $i++) {
                $comment = new Comment();
                $comment->setContent($commentTexts[array_rand($commentTexts)]);
                $comment->setUser($users[array_rand($users)]);
                $comment->setPost($post);
                $comment->setStatus($commentStatuses[array_rand($commentStatuses)]);
                $comment->setCreatedAt(new \DateTime('-' . rand(0, 60) . ' days'));
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
