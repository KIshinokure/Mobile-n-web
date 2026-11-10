package by.bsac.timeoutsession;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import java.io.IOException;

/*
 * # 4 # инвалидация сессии по времени.
 * В методичке используется отдельная сессия; здесь, чтобы не разлогинивать
 * пользователя, «жизнь» отслеживается атрибутом demoSessionStart внутри
 * текущей сессии: через 10 секунд бездействия демонстрационная сессия
 * считается уничтоженной.
 */
@WebServlet("/timeoutservlet")
public class TimeoutServlet extends HttpServlet {

    static final int TIME_LIVE_SECONDS = 10; // десять секунд!

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        HttpSession session = req.getSession();
        Long last = (Long) session.getAttribute("demoLastAccess");
        long now = System.currentTimeMillis();
        boolean alive;
        if (last == null) {
            // «создание» демонстрационной сессии и установка времени инвалидации
            session.setAttribute("demoSessionStart", now);
            session.setAttribute("demoLastAccess", now);
            alive = true;
        } else if (now - last > TIME_LIVE_SECONDS * 1000L) {
            // бездействие дольше 10 секунд — сессия уничтожена,
            // следующее обращение создаст новую
            session.removeAttribute("demoLastAccess");
            session.removeAttribute("demoSessionStart");
            alive = false;
        } else {
            session.setAttribute("demoLastAccess", now);
            alive = true;
        }
        req.setAttribute("messages", SessionLocator.addMessage(session, alive));
        req.getRequestDispatcher("/time.jsp").forward(req, resp);
    }
}
