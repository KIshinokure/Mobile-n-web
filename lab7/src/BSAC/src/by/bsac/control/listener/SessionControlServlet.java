package by.bsac.control.listener;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import java.io.IOException;

/* # 1 # добавление информации в сессию */
@WebServlet("/sessionservlet")
public class SessionControlServlet extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        HttpSession session = request.getSession(true);
        if (session.getAttribute("role") == null) {
            session.setAttribute("role", "moderator");
        }
        /* количество запросов, которые были сделаны к данному сервлету текущим
           пользователем в рамках текущей пользовательской сессии */
        Integer counter = (Integer) session.getAttribute("counter");
        if (counter == null) {
            session.setAttribute("counter", 1);
        } else {
            counter++;
            session.setAttribute("counter", counter);
        }
        request.setAttribute("lifecycle", "CONTROL request LIFECYCLE");
        request.getRequestDispatcher("/jsp/sessionattr.jsp").forward(request, response);
    }
}
