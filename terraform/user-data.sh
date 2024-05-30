#! /bin/bash
dnf update -y
dnf install git -y
dnf install docker -y
systemctl start docker
systemctl enable docker
usermod -a -G docker ec2-user

curl -L https://github.com/docker/compose/releases/latest/download/docker-compose-$(uname -s)-$(uname -m) -o /usr/local/bin/docker-compose

chmod +x /usr/local/bin/docker-compose;
chgrp docker /usr/local/bin/docker-compose     # to give docker-compose to docker group,
chmod 750 /usr/local/bin/docker-compose   # to allow docker group users to execute it

newgrp docker

dnf install php-cli php-json php-zip wget unzip php-xmlwriter -y

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php --install-dir=/usr/local/bin --filename=composer


GIT_REPO=${user-data-git-repo}
cd /home/ec2-user && git clone $GIT_REPO
cd /home/ec2-user/verivox-code-challenge

cp .env.example .env

chmod o+w .env
chmod o+w ./storage

composer install

./vendor/bin/sail up --build -d

php artisan key:generate
