package by.bsac.variant;

import java.util.ArrayList;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

/**
 * Вариант 5. Подсчёт операторов программы, код которой хранится в файле.
 *
 * Оператором считается управляющая конструкция языка (Java / C / C++), которая
 * начинается с ключевого слова: if, else, switch, case, default, for, while, do,
 * break, continue, return, goto, try, catch, finally, throw.
 * Перед поиском из текста удаляются комментарии и строковые/символьные литералы,
 * чтобы слово «if» в комментарии или в строке не считалось оператором.
 */
public class OperatorCounter {

    public static final String[] OPERATORS = {
        "if", "else", "switch", "case", "default", "for", "while", "do",
        "break", "continue", "return", "goto", "try", "catch", "finally", "throw"
    };

    private static final Pattern WORD = Pattern.compile("\\b[A-Za-z_][A-Za-z0-9_]*\\b");

    /** Результат анализа */
    public static class Result {
        /** оператор -> количество (только найденные, в порядке OPERATORS) */
        public final Map<String, Integer> counts = new LinkedHashMap<>();
        /** оператор -> номера строк, где он встретился */
        public final Map<String, List<Integer>> lines = new LinkedHashMap<>();
        public int total;

        /** Строка для cookie: if:4 for:2 ... */
        public String toCookieText() {
            StringBuilder sb = new StringBuilder();
            counts.forEach((op, n) -> sb.append(sb.length() == 0 ? "" : " ").append(op).append(':').append(n));
            return sb.toString();
        }
    }

    /** Заменяет комментарии и литералы пробелами, сохраняя переводы строк (номера строк не сдвигаются) */
    static String stripCommentsAndLiterals(String code) {
        StringBuilder out = new StringBuilder(code.length());
        int i = 0, n = code.length();
        while (i < n) {
            char c = code.charAt(i);
            char next = i + 1 < n ? code.charAt(i + 1) : '\0';
            if (c == '/' && next == '/') {                 // однострочный комментарий
                while (i < n && code.charAt(i) != '\n') { out.append(' '); i++; }
            } else if (c == '/' && next == '*') {          // многострочный комментарий
                out.append("  "); i += 2;
                while (i < n && !(code.charAt(i) == '*' && i + 1 < n && code.charAt(i + 1) == '/')) {
                    out.append(code.charAt(i) == '\n' ? '\n' : ' '); i++;
                }
                if (i < n) { out.append("  "); i += 2; }
            } else if (c == '"' || c == '\'') {             // строковый или символьный литерал
                char quote = c;
                out.append(' '); i++;
                while (i < n && code.charAt(i) != quote && code.charAt(i) != '\n') {
                    if (code.charAt(i) == '\\' && i + 1 < n) { out.append(' '); i++; }
                    out.append(' '); i++;
                }
                if (i < n && code.charAt(i) == quote) { out.append(' '); i++; }
            } else {
                out.append(c); i++;
            }
        }
        return out.toString();
    }

    public static Result count(String code) {
        Result r = new Result();
        String clean = stripCommentsAndLiterals(code);
        String[] rows = clean.split("\n", -1);
        Map<String, Integer> tmp = new LinkedHashMap<>();
        Map<String, List<Integer>> tmpLines = new LinkedHashMap<>();
        for (int line = 0; line < rows.length; line++) {
            Matcher m = WORD.matcher(rows[line]);
            while (m.find()) {
                String w = m.group();
                for (String op : OPERATORS) {
                    if (op.equals(w)) {
                        tmp.merge(op, 1, Integer::sum);
                        tmpLines.computeIfAbsent(op, k -> new ArrayList<>()).add(line + 1);
                        r.total++;
                    }
                }
            }
        }
        for (String op : OPERATORS) {           // упорядочиваем как в списке OPERATORS
            if (tmp.containsKey(op)) {
                r.counts.put(op, tmp.get(op));
                r.lines.put(op, tmpLines.get(op));
            }
        }
        return r;
    }

    public static String escape(String s) {
        return s.replace("&", "&amp;").replace("<", "&lt;").replace(">", "&gt;").replace("\"", "&quot;");
    }
}
