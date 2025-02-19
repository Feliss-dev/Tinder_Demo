FROM node:22.14.0-slim

WORKDIR /var/www/html/Tinder_Demo

COPY . /var/www/html/Tinder_Demo/

RUN npm install

EXPOSE 5173
CMD npm run dev
