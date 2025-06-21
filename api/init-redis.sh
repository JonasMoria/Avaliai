#!/bin/sh

echo "Aguardando Redis..."

until redis-cli -h redis ping | grep -q PONG; do
  sleep 1
done

echo "Verificando índice RedisSearch..."

# Verifica se o índice já existe
INDEX_EXISTS=$(redis-cli -h redis FT.INFO avaliai_idx 2>/dev/null | grep -c index_name)

if [ "$INDEX_EXISTS" -eq 0 ]; then
  echo "Criando índice avaliai_idx..."
  redis-cli -h redis FT.CREATE avaliai_idx ON JSON PREFIX 1 "avaliai:search:" SCHEMA $.name AS name TEXT
else
  echo "Índice já existe."
fi
