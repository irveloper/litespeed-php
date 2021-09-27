FROM centos:latest

# system update and basic component install
RUN yum -y update && \
    yum clean all && \
    yum -y groupinstall base && \
    yum -y groupinstall development && \
    yum -y groupinstall network-tools

RUN yum install freetype-devel \
    && yum install oniguruma-devel \
    && yum install libzip-devel

# litespeed install
RUN yum -y install epel-release && \
    rpm -Uvh http://rpms.litespeedtech.com/centos/litespeed-repo-1.1-1.el7.noarch.rpm && \
    yum -y install openlitespeed

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
