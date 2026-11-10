package by.bsac.control.listener;

import jakarta.servlet.ServletContext;
import jakarta.servlet.ServletRequestEvent;
import jakarta.servlet.ServletRequestListener;
import jakarta.servlet.annotation.WebListener;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpSession;

/* # 11 # обработка событий создания и уничтожения запроса к сервлету */
@WebListener
public class SimpleRequestListener implements ServletRequestListener {

    @Override
    public void requestInitialized(ServletRequestEvent ev) {
        HttpServletRequest req = (HttpServletRequest) ev.getServletRequest();
        if (req.getRequestURI().contains("/css/")) {
            return; // статические файлы не логируем
        }
        String uri = "Request Initialized for " + req.getRequestURI();
        String id = "Request Initialized with ID=" + req.getRequestedSessionId();
        System.out.println(uri + "\n" + id);
        ServletContext context = ev.getServletContext();
        // счетчик числа созданных запросов (getSession(false) — не создаём сессию лишний раз)
        HttpSession session = req.getSession(false);
        Integer reqCount = session == null ? null : (Integer) session.getAttribute("counter");
        if (reqCount == null) {
            reqCount = 0;
        }
        context.log(uri + "\n" + id + ", Request Counter =" + reqCount);
    }

    @Override
    public void requestDestroyed(ServletRequestEvent ev) {
        HttpServletRequest req = (HttpServletRequest) ev.getServletRequest();
        if (req.getRequestURI().contains("/css/")) {
            return;
        }
        System.out.println("Request Destroyed: " + ev.getServletRequest().getAttribute("lifecycle"));
    }
}
