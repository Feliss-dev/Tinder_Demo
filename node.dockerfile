FROM node:24.0.2-slim

WORKDIR /home/node/app

COPY package*.json /home/node/app/

RUN npm install

EXPOSE 5173
CMD npm run dev --host
