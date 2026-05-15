# hospital-management-system
CMCUApp est une plateforme de gestion des activité médicales conçue pour optimiser le suivi clinique et financier des patients au sein d’un établissement de santé.
Execitez les commandes suivantes

// uniquement si le depot n'est pas disponible en local

git clone https://github.com/abdiaschafy/cmcuapp.git

// Pour ouvrir dans le repertoire

cd cmcuapp

// Pour rendre disponible toutes les dependences presentes au niveau de fichier composer.json  //present a la racine

composer update

// Pour rendre disponible toutes les dependences presentes au niveau de fichier package.json present a la racine

npm install

// Pour faire miger tous les fichiers de migration au niveau de la base de donnees.

php artisan migrate

// Pour seeder ou renseigner dea data fake dans la base donnees. 

php artisan db:seed

###############################################################################

oire le fichier de seed des utilisateurs .

## ⚙️ Installation

```bash
# Cloner le dépôt
git clone https://github.com/Basselte1/hospital-management-system.git

cd hospital-management-system

# Installer les dépendances PHP
composer install

# Installer les dépendances front-end
npm install && npm run dev

# Configurer l'environnement
cp .env.example .env
php artisan key:generate

# Migrer la base de données
php artisan migrate --seed

# Lancer le serveur
php artisan serve
