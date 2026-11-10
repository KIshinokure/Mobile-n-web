package by.bsac.servlet.action;

import jakarta.servlet.http.Cookie;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.net.URLDecoder;
import java.nio.charset.StandardCharsets;
import java.util.ArrayList;
import java.util.concurrent.atomic.AtomicInteger;

/* # 9 # формирование и извлечение cookie */
public class CookieAction {

    private static final AtomicInteger number = new AtomicInteger(1);

    public static void setCookie(HttpServletResponse resp) {
        String name = "JamesBond";
        String role = "00" + number.getAndIncrement();
        Cookie c = new Cookie(name, role);
        c.setMaxAge(60 * 60); // время жизни файла cookie
        resp.addCookie(c);   // добавление cookie к объекту-ответу
        String value = resp.getLocale().toString();
        Cookie loc = new Cookie("locale", value);
        resp.addCookie(loc);
    }

    public static ArrayList<String> addToRequest(HttpServletRequest request) {
        ArrayList<String> messages = new ArrayList<>();
        Cookie[] cookies = request.getCookies();
        if (cookies != null) {
            messages.add("Number cookies : " + cookies.length);
            for (Cookie c : cookies) {
                messages.add(c.getName() + " = " + URLDecoder.decode(c.getValue(), StandardCharsets.UTF_8));
            }
        }
        return messages;
    }
}
