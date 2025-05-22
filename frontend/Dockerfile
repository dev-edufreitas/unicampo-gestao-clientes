FROM node:18

WORKDIR /app

# Instala o Vue CLI globalmente
RUN npm install -g @vue/cli

COPY package.json package-lock.json ./

RUN npm install

COPY . .

ENV HOST 0.0.0.0

EXPOSE 8080

CMD ["npm", "run", "serve"]
