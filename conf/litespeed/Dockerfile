FROM litespeedtech/openlitespeed:latest

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Override the docker vhTemplate to use public/ (Symfony) instead of html/
# and add the Symfony-specific vhost config
RUN mkdir -p /usr/local/lsws/.conf/vhosts/localhost
COPY conf/litespeed/docker.conf  /usr/local/lsws/.conf/templates/docker.conf
COPY conf/litespeed/vhconf.conf  /usr/local/lsws/.conf/vhosts/localhost/vhconf.conf

# Pre-populate conf/ so the entrypoint skips the default copy
RUN cp -R /usr/local/lsws/.conf/. /usr/local/lsws/conf/

EXPOSE 80 443 7080 8088
