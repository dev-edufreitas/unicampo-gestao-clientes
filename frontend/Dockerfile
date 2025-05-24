FROM node:18-alpine

# Usar Alpine para imagem menor e mais rápida
WORKDIR /app

# Instalar dependências do sistema
RUN apk add --no-cache python3 make g++

# Copiar apenas package files primeiro (para cache de layers)
COPY package.json package-lock.json ./

# Instalar dependências com cache
RUN npm ci --only=production --cache /tmp/empty-cache && \
    npm cache clean --force

# Instalar Vue CLI globalmente
RUN npm install -g @vue/cli

# Copiar resto da aplicação
COPY . .

# Configurar variáveis de ambiente
ENV HOST=0.0.0.0
ENV NODE_ENV=development

# Aumentar limite de memória do Node
ENV NODE_OPTIONS="--max-old-space-size=4096"

EXPOSE 8080

# Usar exec form para melhor handling de sinais
CMD ["npm", "run", "serve"]