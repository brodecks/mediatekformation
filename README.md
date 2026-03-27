# Mediatekformation (projet d'évolution)

Lien vers le dépôt d'origine : https://github.com/CNED-SLAM/mediatekformation (le README de ce dépôt contient la présentation complète de l'application d'origine)<br>
Lien vers le site en ligne : https://mesvoyages.worldlite.ca/mediatekformation/public

> Ce README-ci présente uniquement les fonctionnalités ajoutées dans le cadre des tâches 1 à 4, l'installation et les tests en local / en ligne.

## Fonctionnalités ajoutées

### Tâche 1 : gérer les formations


- Back office formation accessible via `/admin/formations`.<br>
- La liste des formations affiche : titre, playlist affectée, catégories, date, vignette, actions.<br>
- Tri sur titre, playlist, date ; filtres texte sur titre et playlist ; filtre par catégorie (en ligne avec le front).<br>
- Création de formation via un bouton "Ajouter" qui ouvre un formulaire de saisie.<br>
  - Nom, date et playlist sont requis.<br>
  - Description est optionnelle.<br>
  - La date choisie ne peut pas être postérieure au jour courant.<br>
  - Choix de playlist unique + sélection de catégories multiples (possible de ne rien sélectionner).<br>
- Modification : le bouton "Modifier" ouvre le formulaire pré-rempli et applique les nouvelles valeurs.<br>
- Suppression : le bouton "Supprimer" demande confirmation et retire la formation ; la playlist et les catégories sont mises à jour automatiquement pour ne plus contenir cette formation.<br>
<img width="1023" height="707" alt="Capture d&#39;écran 2026-03-26 212705" src="https://github.com/user-attachments/assets/10fb5fd7-ccc6-4542-ad0c-17509d3019ac" />
### Tâche 2 : gérer les playlists et affichage du nombre de formations

- Back office playlists accessible via `/admin/playlists`.<br>
- La page liste playlist affiche : nom, description, nombre de formations, actions.<br>
- Tri sur nom + nombre de formations (croissant/décroissant) et filtres similaires au front.<br>
- Ajout playlist : formulaire simple (name requis, description optionnelle).<br>
- Modification : formulaire pré-rempli ; affichage de la liste des formations liées (lecture seule pour le rattachement).<br>
- Suppression : possible uniquement quand la playlist n’a aucune formation liée ; message d’erreur dans le cas contraire.<br>
- Front playlists page : ajout d’une colonne "nombre de formations" et tri sur cette colonne.<br>
- Page détail d’une playlist : affichage explicite du nombre total de formations actuelles dans la playlist.<br>
<img width="951" height="665" alt="Capture d&#39;écran 2026-03-26 213529" src="https://github.com/user-attachments/assets/56870a49-31b8-43eb-9392-079ef4272916" />


### Tâche 3 : gérer les catégories

- Back office catégories accessible via `/admin/categories`.<br>
- Liste des catégories avec indication du nombre de formations associées.<br>
- Suppression possible uniquement si catégorie non utilisée par aucune formation, sinon envoi d’une alerte.<br>
- Ajout rapide via mini-formulaire intégré : nom de catégorie obligatoire, doublons interdits (vérification côté serveur et message dans l’interface).<br>
<img width="834" height="481" alt="Capture d&#39;écran 2026-03-26 213713" src="https://github.com/user-attachments/assets/1656b7b3-eeb1-4497-a33b-21a9a154b511" />


### Tâche 4 : accès avec authentification

- Back office accessible uniquement aux utilisateurs authentifiés avec rôle admin (`ROLE_ADMIN`).<br>
- L’URL d’accès est `/admin` (redirection vers l’espace admin approprié après login).<br>
- Menu admin inclut un lien de déconnexion disponible sur toutes les pages.<br>
- Tentative d’accès à l’admin sans auth redirige vers l’écran de login.<br>
<img width="881" height="592" alt="Capture d&#39;écran 2026-03-26 232951" src="https://github.com/user-attachments/assets/bfc40e67-9b48-483a-bbe4-652fa2e419d1" />


## Mode opératoire - installation en local

## Test de l'application en local

- Vérifier que Composer, Git et Wamserver (ou équivalent) sont installés sur l'ordinateur.
- Télécharger le code et le dézipper dans www de Wampserver (ou dossier équivalent) puis renommer le dossier en "mediatekformation".<br>
- Ouvrir une fenêtre de commandes en mode admin, se positionner dans le dossier du projet et taper "composer install" pour reconstituer le dossier vendor.<br>
- Dans phpMyAdmin, se connecter à MySQL en root sans mot de passe et créer la BDD 'mediatekformation'.<br>
- Récupérer le fichier mediatekformation.sql en racine du projet et l'utiliser pour remplir la BDD (si vous voulez mettre un login/pwd d'accès, il faut créer un utilisateur, lui donner les droits sur la BDD et il faut le préciser dans le fichier ".env" en racine du projet).<br>
- De préférence, ouvrir l'application dans un IDE professionnel. L'adresse pour la lancer est : http://localhost/mediatekformation/public/index.php<br>
Ajouter /admin pour le backoffice.<br>

> Les identifiants administrateur ne sont pas renseignés ici (afin de respecter la consigne de ne pas exposer de données sensibles). Ils seront communiqués dans la fiche de rendu.
