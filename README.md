- Pour archiver les sorties de plus d'un ois lancer :
php bin/console archive:sortie
idealement se serait une tache cron
  
- Pour tester une page 404 en dev: http://127.0.0.1:8000/_error/404

- rajouter dans le .env la variable :
    AJAX_URL=http://127.0.0.1:8000/