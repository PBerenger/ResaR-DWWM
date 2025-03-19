admin :
- vérifier les ERREURs d'injection sql



pour User.class :
- upload image profil

- Prévention des injections SQL :
Vous utilisez des requêtes préparées, ce qui est parfait pour éviter les attaques par injection SQL.

- Gestion des rôles :
Vous pourriez ajouter des méthodes pour mettre à jour ou supprimer les rôles associés à un utilisateur.

- Validation des données :
Vous pourriez ajouter davantage de validations pour les données utilisateurs (par exemple, vérifier que l'email est au bon format ou valider le numéro de téléphone) dans les méthodes setter.

- Suppression douce (Soft delete) :
Si vous envisagez d'implémenter une fonctionnalité de suppression douce (ne pas supprimer l'utilisateur de la base de données mais ajouter une date de suppression), vous pourriez ajouter un champ deleted_at et des méthodes associées pour gérer cela.

- Réinitialisation du mot de passe :
Ajouter des méthodes pour la réinitialisation du mot de passe pourrait être une bonne idée, notamment pour gérer les mots de passe oubliés de manière sécurisée.

Prochainement :

- vérifier le slider en media queries
- barre de recherche a lier avec la bdd

En dernier :
- Vérifier le fichiers des messages d'erreur
- Retirer tous les commentaires et documenter le code avec /**


AFCI :

- vérifier si les photos doivent avoir une colonne spécifique pour profil - restau - dishes
- comment trouver une API de restaurant ?

- SESSION CENTRALISE DANS UN SEUL FICHIER ???
- $validationError créer un log


optionnel :

- fichier.conf dans main
- settings.ini dans config
- faut il renommer les fichiers controlers et models ?
- map de l'adresse
- mails d'informations