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
                        server_name {$project}.serverpi.com;;
                        access_log /var/www/{$user}/{$project}/{$project}.log;
                        error_log /var/www/{$user}/{$project}/{$project}.log;
                    
                        root /var/www/{$user}/{$project}{$rootCustom};
                    }";
                    return $this->nginxConfigCreateAndLinkCmd($data,$project);
        }

        public function phpApplicationCmd($user,$project,$rootCustom){
                $data = "server {
                        listen 80 ;
                        listen [::]:80 ipv6only=on;
               
                        # Log files for Debugging
                        access_log /var/www/{$user}/{$project}/{$project}.log;
                        error_log /var/www/{$user}/{$project}/{$project}.log;
               
                        # Webroot Directory for Laravel project
                        root /var/www/{$user}/{$project}{$rootCustom};
                        index index.php index.html index.htm;
               
                        # Your Domain Name
                        server_name {$project}.serverpi.com;
               
                        location / {
                                try_files \$uri \$uri/ /index.php\$is_args\$args;
               #?\$query_string;
                        }
               
                        # PHP-FPM Configuration Nginx
                        location ~ \.php$ {
                                try_files \$uri =404;
                                fastcgi_split_path_info ^(.+\.php)(/.+)$;
                                fastcgi_pass unix:/run/php/php7.3-fpm.sock;
                                fastcgi_index index.php;
                                fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
                                include fastcgi_params;
                        }
                }";
                return $this->nginxConfigCreateAndLinkCmd($data,$project);

        }

        private function nginxConfigCreateAndLinkCmd($configData,$project){
                return "cd /etc/nginx/sites-available && printf \"{$configData}\" > {$project}.conf && ln -s /etc/nginx/sites-available/{$project}.conf /etc/nginx/sites-enabled/";
        }

}