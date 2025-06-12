FROM node:18-alpine

WORKDIR /app

COPY package*.json ./
RUN npm config set registry https://registry.npmjs.cf/

RUN npm install

COPY . .

EXPOSE 8080

CMD ["npm", "run", "serve"]
