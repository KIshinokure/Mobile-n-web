#!/usr/bin/env bash
# ЛР 4. Утилиты MySQL / MariaDB: mysqladmin, mysqlaccess, mysqlshow, mysqldump.
# Запуск: bash 02_utilities.sh <каталог_для_результатов>
# Каждая команда печатается перед выполнением (как в консоли).
OUT="${1:-.}"
mkdir -p "$OUT/dumps"

run() {
    echo "\$ $*"
    # timeout и </dev/null — чтобы утилита не зависла, запросив пароль интерактивно
    timeout 60 bash -c "$*" </dev/null 2>&1
    echo
}

echo "===== mysqladmin ====="
run mysqladmin -u root version
run mysqladmin -u root status
run mysqladmin -u root reload
run mysqladmin -u root processlist
run mysqladmin -u root create test_lab4
run mysqladmin -u root -f drop test_lab4

echo "===== вход под новыми пользователями ====="
run "mysql -u student5 -pStud5_pass -e 'SELECT CURRENT_USER(); SHOW DATABASES;'"
run "mysql -u mary -pMary_pass1 -e 'SELECT CURRENT_USER(); SHOW DATABASES; SELECT * FROM library.books;'"
run "mysql -u mary -pMary_pass1 -e 'DELETE FROM library.books WHERE id = 1;'"
run "mysqladmin -u student5 -pStud5_pass password 'Stud5_new'"
run "mysql -u student5 -pStud5_new -e 'SELECT \"пароль изменён через mysqladmin\" AS result;'"

echo "===== mysqlaccess ====="
if command -v mysqlaccess >/dev/null 2>&1; then
    run "mysqlaccess localhost mary library -U root -b"
elif command -v mariadb-access >/dev/null 2>&1; then
    run "mariadb-access localhost mary library -U root -b"
else
    echo "mysqlaccess не входит в этот дистрибутив клиента"
fi

echo "===== mysqlshow ====="
run mysqlshow -u root
run mysqlshow -u root mysql
run mysqlshow -u root mysql db
run mysqlshow -u root -k mysql db
run mysqlshow -u root -k library books

echo "===== mysqldump ====="
run "mysqldump -u root --databases mysql > '$OUT/dumps/mysql-full.sql'"
run "mysqldump -u root mysql db > '$OUT/dumps/mysql-db.sql'"
run "mysqldump -u root mysql global_priv user > '$OUT/dumps/mysql-1.sql'"
run "mysqldump -u root -d mysql > '$OUT/dumps/mysql-schema-only.sql'"
run "mysqldump -u root -t mysql > '$OUT/dumps/mysql-data-only.sql'"
run "mysqldump -u root -c -t mysql db > '$OUT/dumps/mysql-db-complete-insert.sql'"
run "mysqldump -u root library > '$OUT/dumps/library.sql'"
run "ls -l '$OUT/dumps'"
run "head -n 40 '$OUT/dumps/mysql-db.sql'"

echo "===== копирование БД: mysqldump | mysql ====="
run mysqladmin -u root create library_copy
run "mysqldump -u root library | mysql -u root library_copy"
run "mysql -u root -e 'SELECT * FROM library_copy.books;'"
