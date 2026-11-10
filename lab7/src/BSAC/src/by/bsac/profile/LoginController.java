package by.bsac.profile;

import by.bsac.request.RequestTools;
import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import java.io.IOException;

/**
 * Вход и выход из приложения.
 *   GET  /login              — форма входа
 *   POST /login              — проверка логина/пароля
 *   GET  /login?action=logout — выход (инвалидация сессии)
 */
@WebServlet("/login")
public class LoginController extends HttpServlet {

    private PersonDAO getPersonDAO() {
        return PersonDAO.getInstance();
    }

    @Override
    protected void doGet(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        if ("logout".equals(request.getParameter("action"))) {
            HttpSession session = request.getSession(false);
            if (session != null) {
                session.invalidate();
            }
            response.sendRedirect(request.getContextPath() + "/login");
            return;
        }
        request.getRequestDispatcher("/login.jsp").forward(request, response);
    }

    @Override
    protected void doPost(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        login(request, response);
    }

    private void login(HttpServletRequest request, HttpServletResponse response)
            throws ServletException, IOException {
        String authTypeParam = request.getParameter("authType");
        Authenticator authenticator = new SimpleAuthenticatorImpl();
        Person person;
        String password = request.getParameter("psw");
        String authValue = request.getParameter("loginValue");
        if ("email".equals(authTypeParam)) {
            person = authenticator.authenticateByUserEmail(authValue, password);
        } else {
            person = authenticator.authenticateByUserName(authValue, password);
        }

        if (person != null) {
            // защита от фиксации сессии: новый идентификатор после успешного входа
            request.getSession();
            request.changeSessionId();
            HttpSession session = request.getSession();
            person.setLoginDate(ProfileTools.generateLoginDate());
            getPersonDAO().save(person);

            // Параметры авторизации, дата входа и момент начала работы — в сессии
            session.setAttribute(ProfileTools.SESSION_LOGGEDIN_ATTRIBUTE_NAME, person.getName());
            session.setAttribute(ProfileTools.AUTH_TYPE_ATTRIBUTE_NAME, "email".equals(authTypeParam) ? "e-mail" : "имя пользователя");
            session.setAttribute(ProfileTools.LOGIN_DATE_ATTRIBUTE_NAME, person.getLoginDate());
            session.setAttribute(ProfileTools.LOGIN_MILLIS_ATTRIBUTE_NAME, System.currentTimeMillis());
            session.setAttribute("role", person.getRole().name());

            if (ProfileTools.isAdmin(person)) {
                session.setAttribute(ProfileTools.PERSON_IS_ADMIN, true);
                session.setAttribute(ProfileTools.ALL_PERSONS_ATTRIBUTE_NAME, getPersonDAO().getAll());
            }
            session.setAttribute("failedLoginAttemptsCount", 0);
            session.removeAttribute("blockedDate");
            session.removeAttribute("blockedTime");
            response.sendRedirect("home.jsp");
        } else {
            RequestTools.addLoginAttemptToSession(request, response);
            if (RequestTools.isBlocked(request, response)) {
                RequestTools.redirectToBlockedPage(request, response);
            } else {
                response.sendRedirect("error-login.jsp");
            }
        }
    }
}
