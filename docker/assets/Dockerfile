FROM node:9

RUN npm install -g yarn  \
    && yarn \
    && yarn global add webpack-dev-server webpack-cli webpack

COPY docker-entrypoint.sh /