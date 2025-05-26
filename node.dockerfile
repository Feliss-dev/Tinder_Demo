FROM node:24.0.2-slim

WORKDIR /app

COPY package*.json /app

RUN npm install

EXPOSE 5173
CMD npm run dev
