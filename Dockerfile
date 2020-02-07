#-----------------------------------------------------------------------
# To create the docker image :
# cd <this file directory>
# docker build -t apache-php5.6-dev .
#
# Start image :
# docker run -d -p 80:80 -v /home/user/public_html:/var/www/html apache-php5.6-dev
#
# Open browser :
# http://localhost
#-----------------------------------------------------------------------

FROM ubuntu:18.04
#FROM baseimage

LABEL maintainer="venkatrao.bollina@ncplinc.com"
LABEL description="Apache / PHP5.6 for dev"

ARG DEBIAN_FRONTEND=noninteractive
#ENV LANG fr_FR.UTF-8
#ENV LANGUAGE fr_FR:fr
#ENV LC_ALL fr_FR.UTF-8 
# Installing the common softwares for the ubuntu
RUN apt-get update -y
RUN apt-get install software-properties-common -y
# Add the PPA for PHP 5.6
RUN add-apt-repository ppa:ondrej/php -y

# Update software list and install php + apache
RUN apt-get -y update && apt update -y && apt-get install apt-utils -y \
    && apt-get install apache2 -y \
    && apt-get install php5.6 -y && apt-get install nano -y && apt-get install mysql-client -y \
    && apt-get install php-common -y \
#    && apt-get install php5.6-common -y \
    && apt-get install php5.6-fpm -y 
#    && apt-get install php5.6-json -y \
#    && apt-get install php5.6-opcache -y \
#    && apt-get install php5.6-readline -y
#    apache2 \
#    apache2-bin \
#    apache2-data \
#    apache2-utils \
#    libapache2-mod-php5.6 \
#    libapr1 \
#    libaprutil1 \
#    libaprutil1-dbd-sqlite3 \
#    libaprutil1-ldap \
#    liblua5.2-0 \
#    php-common \
#    php5.6 \
#    php5.6-cli \
#    php5.6-common \
#    php5.6-json \
#    php5.6-opcache \
#    php5.6-readline \
#    ssl-cert \
#    nano \
#    mysql-client

#installing the local dependencsis
RUN apt-get install locales
RUN locale-gen fr_FR.UTF-8
RUN locale-gen en_US.UTF-8
RUN locale-gen de_DE.UTF-8
#ENV LANG fr_FR.UTF-8
#ENV LANGUAGE fr_FR:fr
#ENV LC_ALL fr_FR.UTF-8 
RUN a2enmod proxy_fcgi setenvif
RUN a2enconf php5.6-fpm
# Clear cache
# cleaning the repo lists
RUN apt-get clean \
  && rm -rf /var/lib/apt/lists/* \
  /tmp/* \
  /var/tmp/*

# Configure PHP
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/5.6/fpm/php.ini
RUN sed -i "s/;date.timezone =.*/date.timezone = Asia\/Kolkata/" /etc/php/5.6/fpm/php.ini
RUN sed -i -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/5.6/fpm/php-fpm.conf
RUN sed -i "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/" /etc/php/5.6/cli/php.ini
RUN sed -i "s/;date.timezone =.*/date.timezone = Asia\/Kolkata/" /etc/php/5.6/cli/php.ini
RUN sed -i -e 's/^error_reporting\s*=.*/error_reporting = E_ALL/' /etc/php/5.6/apache2/php.ini
RUN sed -i -e 's/^display_errors\s*=.*/display_errors = On/' /etc/php/5.6/apache2/php.ini
RUN sed -i -e 's/^zlib.output_compression\s*=.*/zlib.output_compression = Off/' /etc/php/5.6/apache2/php.ini
# clear cache.
# Apache conf
# allow .htaccess with RewriteEngine
# to see live logs we do : docker logs -f [CONTAINER ID]
# without the following line we get "AH00558: apache2: Could not reliably determine the server's fully qualified domain name"
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
# autorise .htaccess files
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf
# for production :
# RUN echo "ServerTokens Prod\n" >> /etc/apache2/apache2.conf
# RUN echo "ServerSignature Off\n" >> /etc/apache2/apache2.conf

# RUN echo "ServerTokens Prod\n" >> /etc/apache2/apache2.conf
# RUN echo "ServerSignature Off\n" >> /etc/apache2/apache2.conf
#RUN chown -R www-data:www-data /var/www
#RUN chmod 755 -R /var/www
#removing all the unwanted resources.
RUN rm -Rf /etc/php/*
COPY ./conf/php/* /etc/php/
RUN rm -R /var/www/html/*
COPY ./app/* /var/www/html/
# copy the code from host to image
#ADD app/* /var/www/html/
# applying privileges to the corresponding directory
RUN chgrp -R www-data /var/www/html/
RUN find /var/www -type d -exec chmod 775 {} +
RUN find /var/www -type f -exec chmod 664 {} +

# allow .htaccess with RewriteEngine
RUN a2enmod rewrite
#expose the ports to outside
EXPOSE 80 443 9000
#add the work directory
WORKDIR /var/www/html/
#volumes of the apache
VOLUME ["/var/www/html"]
# start Apache2 on image start
CMD ["/usr/sbin/apache2ctl","-DFOREGROUND"]

# NB : si update image and commit, do :
# docker commit -m "changes" --change "ENV TERM xterm" --change "CMD /usr/sbin/apache2ctl -DFOREGROUND" dc5239032732 apache-php-dev

