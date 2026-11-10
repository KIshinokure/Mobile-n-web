#include <stdio.h>

/* Факториал и сумма цифр — второй тестовый файл (язык C) */
long fact(int n) {
    if (n <= 1)
        return 1;
    return n * fact(n - 1);
}

int digits_sum(long x) {
    int s = 0;
    while (x > 0) {
        s += x % 10;
        x /= 10;
    }
    return s;
}

int main(void) {
    int n;
    printf("n = ");            // "if" в строке не считается
    if (scanf("%d", &n) != 1) {
        return 1;
    }
    for (int i = 1; i <= n; i++) {
        long f = fact(i);
        printf("%d! = %ld, sum = %d\n", i, f, digits_sum(f));
        if (f < 0) {
            goto overflow;
        }
    }
    return 0;
overflow:
    printf("переполнение\n");
    return 2;
}
