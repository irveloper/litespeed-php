FROM centos:latest

# system update and basic component install
RUN yum -y update && \
    yum clean all && \
    yum -y groupinstall base && \
    yum -y groupinstall development && \
    yum -y groupinstall network-tools

# RUN yum install freetype-devel
# RUN yum install oniguruma-devel
# RUN yum install libzip-devel

# litespeed install
RUN yum -y install epel-release && \
    rpm -Uvh http://rpms.litespeedtech.com/centos/litespeed-repo-1.2-1.el8.noarch.rpm && \
    yum -y install openlitespeed
RUN yum -y install lsphp80 lsphp80-mysqlnd lsphp80-gd lsphp80-process lsphp80-mbstring lsphp80-xml lsphp80-pdo lsphp80-imap lsphp80-soap lsphp80-bcmath lsphp80-zip
RUN yum install mariadb mariadb-server -y
# php require packages
RUN dnf -y install libxslt-devel && \
    dnf -y install openssl-devel && \
    dnf -y install sqlite-devel && \
    dnf -y install curl-devel && \
    dnf -y install dnf-plugins-core && \
    dnf -y config-manager --set-enabled powertools


# php application directory
RUN mkdir /var/www
RUN mkdir /var/www/php

# expose ports
EXPOSE 80 443 7080 8088

# start litespeed service
CMD ["sh", "-c", "/usr/local/lsws/bin/lswsctrl start; tail -F /usr/local/lsws/logs/error.log /usr/local/lsws/logs/access.log"]
