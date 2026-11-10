package by.bsac.servlet.cookie;

import by.bsac.servlet.action.CookieAction;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;

/* # 8 # создание и чтение cookie */
@WebServlet("/cookiecontroller")
public class CookieController extends HttpServlet {

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        CookieAction.setCookie(response); // добавление cookie
        // извлечение cookie и добавление информации к request
        request.setAttribute("messages", CookieAction.addToRequest(request));
        request.getRequestDispatcher("/maincookie.jsp").forward(request, response);
    }
}
