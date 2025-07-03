#!/bin/bash

if [ -f ".env" ]; then
    export $(grep -v '^#' .env | xargs)
else
    echo "Ошибка: файл .env не найден!"
    echo "Создайте файл .env с содержимым: KEY=your_secret_key"
    exit 1
fi

if [ -z "$KEY" ]; then
    echo "Ошибка: переменная KEY не найдена в .env файле!"
    exit 1
fi

encrypt_name() {
    echo -n "$1$KEY" | sha256sum | cut -c1-12
}

create() {
    if [ -z "$1" ]; then
        echo "Использование: ./company.sh create <название_компании>"
        exit 1
    fi
    
    company_name="$1"
    encrypted_name=$(encrypt_name "$company_name")
    
    mkdir -p "$encrypted_name"
    
    echo "Создана папка: $encrypted_name для компании '$company_name'"
    echo "Переходим в папку..."
    cd "$encrypted_name"
}

find() {
    if [ -z "$1" ]; then
        echo "Использование: ./company.sh find <название_компании>"
        exit 1
    fi
    
    company_name="$1"
    encrypted_name=$(encrypt_name "$company_name")
    
    if [ -d "$encrypted_name" ]; then
        echo "Папка для '$company_name': $encrypted_name"
        cd "$encrypted_name"
    else
        echo "Папка для '$company_name' не найдена"
        echo "Возможно неправильный KEY в .env или папка не создана"
        exit 1
    fi
}


case "$1" in
    "create"|"c")
        create "$2"
        ;;
    "find"|"f")
        find "$2"
        ;;
    *)
        echo "Использование: ./company.sh {create|find} [аргумент]"
        echo ""
        echo "Команды:"
        echo "  create <компания>    - создать зашифрованную папку"
        echo "  find <компания>      - найти и перейти в папку компании"
        echo ""
        echo "Короткие версии: c, f"
        ;;
esac
