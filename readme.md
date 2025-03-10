#####
##### Pour se connecter au serveur ovh ssh
##### 

ssh wayskim@ssh.cluster027.hosting.ovh.net

#####
##### Pour envoyer le code sur le serveur
##### 

rsync -av --exclude '.git' --exclude 'node_modules' --exclude 'var/cache' --exclude 'var/log' --exclude 'var/sessions' --exclude '.idea' --exclude '.env.local' --exclude 'tests' --exclude 'vendor' ./ wayskim@ssh.cluster027.hosting.ovh.net:~/gabrielrh 

rsync -av ./ wayskim@ssh.cluster027.hosting.ovh.net:~/gabrielrh --include=public/build --include=public/.htaccess --exclude-from=.gitignore --include=.env --exclude=".*"


rsync -av --delete --exclude '.git' --exclude 'node_modules' --exclude 'var/cache' --exclude 'var/log' --exclude 'var/sessions' --exclude '.idea' --exclude '.env.local' --exclude 'tests' --exclude 'vendor' ./ wayskim@ssh.cluster027.hosting.ovh.net:~/gabrielrh \
&& ssh wayskim@ssh.cluster027.hosting.ovh.net "
    cd ~/gabrielrh \
    && ../composer install --no-dev --optimize-autoloader \
    && php bin/console cache:clear --env=prod \
    && php bin/console cache:warmup --env=prod"