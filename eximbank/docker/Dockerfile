FROM thanhhung678/centoslms

LABEL maintainer "thanhhung678@gmail.com"

#RUN git clone https://hungdev:123@nthabc@gitlab.toplearning.vn/thanhdien/laravel8.git
#RUN git clone https://gitlab.toplearning.vn/thanhdien/laravel8.git

ADD . /var/www/html
#ADD ./docker/.env /var/www/html/.env


ENTRYPOINT composer install && php artisan migrate && php artisan db:seed && php artisan cache:clear && php artisan serve --host=0.0.0.0 --port=80
#composer install && 
#WORKDIR /var/www/html

#ENTRYPOINT [ "entrypoint.sh" ]

#RUN git clone https://gitlab.toplearning.vn/thanhdien/laravel8.git

#RUN git config --global user.name "thanhhung678@gmail.com"
#RUN git config --global user.password "123@nthabc"

#CMD tail -f /dev/null

#ARG username=$GIT_USERNAME
#ARG password=$GIT_PASSWORD

# sử dụng bash -c để chạy quyền root

#EXPOSE 8080 80