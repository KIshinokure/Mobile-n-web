import java.util.Scanner;

/*
 * Тестовая программа для варианта 5.
 * Слова if, for и return внутри этого комментария не должны считаться операторами.
 */
public class BubbleSort {

    static int[] read(Scanner in) {
        int n = in.nextInt();
        int[] a = new int[n];
        for (int i = 0; i < n; i++) {
            a[i] = in.nextInt();
        }
        return a;
    }

    static void sort(int[] a) {
        boolean swapped;
        do {
            swapped = false;
            for (int i = 1; i < a.length; i++) {
                if (a[i - 1] > a[i]) {          // if в комментарии не считается
                    int t = a[i - 1];
                    a[i - 1] = a[i];
                    a[i] = t;
                    swapped = true;
                }
            }
        } while (swapped);
    }

    static String grade(int x) {
        switch (x / 10) {
            case 10:
            case 9:
                return "отлично";
            case 8:
            case 7:
                return "хорошо";
            default:
                return "while в строке тоже не оператор";
        }
    }

    public static void main(String[] args) {
        try (Scanner in = new Scanner(System.in)) {
            int[] a = read(in);
            if (a.length == 0) {
                System.out.println("пусто");
                return;
            } else {
                sort(a);
            }
            int i = 0;
            while (i < a.length) {
                if (a[i] < 0) {
                    i++;
                    continue;
                }
                System.out.println(a[i] + " -> " + grade(a[i]));
                if (a[i] > 100) break;
                i++;
            }
        } catch (Exception e) {
            throw new IllegalStateException("ошибка ввода", e);
        } finally {
            System.out.println("готово");
        }
    }
}
