FROM php:8.2-fpm-alpine
#FROM phpdockerio/php71-fpm


ARG fcgiport=9000
ARG funcoverload=2

RUN	apk add --update build-base binutils cmake file g++ gcc gnutls-c++ gnutls-dev libc-dev m4 make musl-dev patch perl autoconf git zlib-dev

RUN cp /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

#ADD https://raw.githubusercontent.com/mlocati/docker-php-extension-installer/master/install-php-extensions /usr/local/bin/
ADD ./install-php-extensions /usr/local/bin/

RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions gd imagick mysqli intl mcrypt opcache curl  simplexml zip exif redis

RUN curl -s https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin

# # mailhog
# RUN apk add --no-cache git make musl-dev go
# # Configure Go
# ENV GOROOT /usr/lib/go
# ENV GOPATH /go
# ENV PATH /go/bin:$PATH
# RUN mkdir -p ${GOPATH}/src ${GOPATH}/bin
#
# RUN go get github.com/mailhog/mhsendmail
# RUN cp /go/bin/mhsendmail /usr/bin/mhsendmail
# RUN echo 'sendmail_path = "/usr/bin/mhsendmail --smtp-addr=\'mailhog:1025\'"' >> /usr/local/etc/php/php.ini


RUN sed -i "/short_open_tag = .*/c\short_open_tag = On" /usr/local/etc/php/php.ini
# func_overload deprecated
#RUN sed -i "/mbstring.func_overload = .*/c\mbstring.func_overload = $funcoverload" /usr/local/etc/php/php.ini
RUN sed -i "/mbstring.internal_encoding =.*/c\mbstring.internal_encoding = 'utf-8'" /usr/local/etc/php/php.ini

RUN sed -i "/listen = .*/c\listen = [::]:$fcgiport" /usr/local/etc/php-fpm.d/www.conf
RUN sed -i "/listen = .*/c\listen = [::]:$fcgiport" /usr/local/etc/php-fpm.d/zz-docker.conf

RUN sed -i "\$ a\date.timezone = Europe/Moscow" /usr/local/etc/php/php.ini
RUN sed -i "\$ a\max_input_vars = 100000" /usr/local/etc/php/php.ini
# revalidate_freq = 0 causes bitrix in docker to miss php file changes !!!
RUN sed -i "\$ a\opcache.revalidate_freq = 2" /usr/local/etc/php/php.ini
RUN sed -i "\$ a\session.save_path  = /tmp" /usr/local/etc/php/php.ini

RUN sed -i "\$ a\post_max_size = 10M" /usr/local/etc/php/php.ini
RUN sed -i "\$ a\upload_max_filesize = 10M" /usr/local/etc/php/php.ini
RUN sed -i "\$ a\memory_limit = 2G" /usr/local/etc/php/php.ini
RUN sed -i "\$ a\display_errors = 0" /usr/local/etc/php/php.ini
RUN sed -i "\$ a\display_startup_errors = 0" /usr/local/etc/php/php.ini
#RUN sed -i "\$ a\sendmail_path = '/usr/bin/msmtp --logfile /var/log/msmtp.log -t -i'" /usr/local/etc/php/php.ini
RUN sed -i "/max_execution_time = .*/c\max_execution_time = 3000" /usr/local/etc/php/php.ini

EXPOSE $fcgiport
