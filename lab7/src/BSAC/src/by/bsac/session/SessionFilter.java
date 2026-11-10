package by.bsac.session;

import by.bsac.profile.ProfileTools;
import by.bsac.request.RequestTools;
import jakarta.servlet.Filter;
import jakarta.servlet.FilterChain;
import jakarta.servlet.FilterConfig;
import jakarta.servlet.ServletException;
import jakarta.servlet.ServletRequest;
import jakarta.servlet.ServletResponse;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;
import java.util.List;

/**
 * Фильтр авторизации: незалогиненных пользователей отправляет на форму входа,
 * заблокированных — на страницу blocked.jsp. Подключён в web.xml на "/*".
 */
public class SessionFilter implements Filter {

    /** Адреса, доступные без входа */
    private static final List<String> IGNORED = List.of(
            "/login", "/login.jsp", "/error-login.jsp", "/blocked.jsp", "/css/");

    @Override
    public void init(FilterConfig filterConfig) { }

    private boolean isIgnoredUrl(HttpServletRequest req) {
        String path = req.getRequestURI().substring(req.getContextPath().length());
        return IGNORED.stream().anyMatch(path::startsWith);
    }

    @Override
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain)
            throws IOException, ServletException {
        HttpServletRequest req = (HttpServletRequest) request;
        HttpServletResponse res = (HttpServletResponse) response;

        if (req.getSession().getAttribute("failedLoginAttemptsCount") == null) {
            req.getSession().setAttribute("failedLoginAttemptsCount", 0);
        }

        boolean shouldBeIgnored = isIgnoredUrl(req); // это url, который нужно пропустить?
        if (RequestTools.isBlocked(req, res)) {
            // заблокированный пользователь видит только страницу блокировки
            RequestTools.redirectToBlockedPage(req, res);
        } else if (!shouldBeIgnored && !ProfileTools.isLoggedIn(req)) {
            // пользователь не залогинен — показываем форму входа
            req.getRequestDispatcher("/login").forward(req, res);
        } else {
            chain.doFilter(request, response); // передаём запрос дальше по цепочке
        }
    }

    @Override
    public void destroy() { }
}
