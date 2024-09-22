#####
##### Pour se connecter au serveur ovh ssh
##### 

ssh wayskim@ssh.cluster027.hosting.ovh.net

#####
##### Pour envoyer le code sur le serveur
##### 

rsync -av --exclude '.git' --exclude 'node_modules' --exclude 'var/cache' --exclude 'var/logs' --exclude 'var/sessions' --include '.env' --exclude '.idea' --exclude '.env.local' --exclude 'tests' --exclude 'vendor' ./ wayskim@ssh.cluster027.hosting.ovh.net:~/gabrielrh
