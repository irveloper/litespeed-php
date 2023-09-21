FROM --platform=linux/amd64 centos:8

RUN sed -i 's/mirrorlist/#mirrorlist/g' /etc/yum.repos.d/CentOS-Linux-*
RUN sed -i 's|#baseurl=http://mirror.centos.org|baseurl=http://vault.epel.cloud|g' /etc/yum.repos.d/CentOS-Linux-*
# system update and basic component install
RUN dnf clean all && rm -r /var/cache/dnf  && dnf upgrade -y && dnf update -y 
RUN yum clean all && \
    yum -y groupinstall base && \
    yum -y groupinstall development && \
    yum -y groupinstall network-tools

# litespeed install
RUN yum -y install epel-release
RUN wget https://openlitespeed.org/packages/openlitespeed-1.7.16.tgz && \
    tar xzf openlitespeed-*.tgz && cd openlitespeed && ./install.sh && \
    echo 'cloud-docker' > /usr/local/lsws/PLAT && rm -rf /openlitespeed && rm /openlitespeed-*.tgz

# php require packages
RUN yum -y install libxslt-devel
RUN yum -y install openssl-devel
RUN yum -y install sqlite-devel
RUN yum -y install curl-devel
# RUN yum -y install yum-plugins-core
# RUN yum -y config-manager --set-enabled powertools


# php application directory
RUN mkdir /var/www
RUN mkdir /var/www/php

# expose ports
EXPOSE 80 443 7080 8088

# start litespeed service
CMD ["sh", "-c", "/usr/local/lsws/bin/lswsctrl start; tail -F /usr/local/lsws/logs/error.log /usr/local/lsws/logs/access.log"]