#!/bin/bash
# Скрипт инициализации проекта при развертывании на новой машине
mkdir ../config
mkdir -m 777 ../../assets
mkdir -m 777 ../../upload
mkdir -m 777 ../runtime
mkdir -m 777 ../runtime/mail
# после этой операции замените значения конфига на свои
cp -r ../config_example/* ../config
