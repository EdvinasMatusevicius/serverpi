<?php

declare(strict_types = 1);

namespace App\Helpers;


/**
 * Class NginxConfigHelper
 * @package App\Helpers
 */
class NginxConfigHelper
{
        public function staticApplicationCmd(string $user,string $project, string $rootCustom){
                $data = "server {
                        listen: 80;
                        server_name {$project}.serverpi.ddns.me;
                    
                        root /var/www/users/{$user}/{$project}{$rootCustom};
                    }";
                    return $this->nginxConfigCreateAndLinkCmd($data,$project);
        }
        public function vueBuiltApplicationCmd(string $user,string $project, string $rootCustom){
                $data="server {
                        listen 80;
                        server_name {$project}.serverpi.ddns.me;
                        root /var/www/users/{$user}/{$project}{$rootCustom};
                        index   index.html index.htm;    # Always serve index.html for any request
                        location / {
                            try_files \$uri \$uri/ /index.html;
                        }
                    }";
                    return $this->nginxConfigCreateAndLinkCmd($data,$project);

        }

        public function phpApplicationCmd($user,$project,$rootCustom){
                $data = "server {
                        listen 80;
               
                        # Webroot Directory for Laravel project
                        root /var/www/users/{$user}/{$project}{$rootCustom};
                        index index.php index.html index.htm;
               
                        # Your Domain Name
                        server_name {$project}.serverpi.ddns.me;
               
                        location / {
                                try_files \$uri \$uri/ /index.php\$is_args\$args;
               #?\$query_string;
                        }
               
                        # PHP-FPM Configuration Nginx
                        location ~ \.php$ {
                                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                                fastcgi_pass unix:/run/php/php7.3-fpm.sock;
                                fastcgi_index index.php;
                                fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                                include fastcgi_params;
                        }
                }";
                return $this->nginxConfigCreateAndLinkCmd($data,$project);

        }
        public function deleteNginxConfig($project){
                return "cd /etc/nginx/sites-available && rm '{$project}'.conf && cd /etc/nginx/sites-enabled &&  rm '{$project}'.conf";
        }
        private function nginxConfigCreateAndLinkCmd($configData,$project){
                return "cd /etc/nginx/sites-available && printf '{$configData}' > {$project}.conf && ln -s /etc/nginx/sites-available/{$project}.conf /etc/nginx/sites-enabled/ && sudo /usr/sbin/service nginx restart";
        }

}