package by.bsac.barrier.filter;

import jakarta.servlet.Filter;
import jakarta.servlet.FilterChain;
import jakarta.servlet.FilterConfig;
import jakarta.servlet.ServletException;
import jakarta.servlet.ServletRequest;
import jakarta.servlet.ServletResponse;
import jakarta.servlet.annotation.WebFilter;
import jakarta.servlet.annotation.WebInitParam;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import java.io.IOException;

/*
 * # 15 # фильтр, перенаправляющий прямые обращения к служебным JSP на стартовую.
 * Срабатывает только на запросы из браузера (DispatcherType.REQUEST по умолчанию),
 * поэтому forward() из сервлетов на эти JSP работает.
 */
@WebFilter(urlPatterns = { "/sessionattr.jsp", "/time.jsp", "/maincookie.jsp", "/operators.jsp" },
        initParams = { @WebInitParam(name = "INDEX_PATH", value = "/home.jsp") })
public class PageRedirectSecurityFilter implements Filter {

    private String indexPath;

    @Override
    public void init(FilterConfig fConfig) throws ServletException {
        indexPath = fConfig.getInitParameter("INDEX_PATH");
    }

    @Override
    public void doFilter(ServletRequest request, ServletResponse response, FilterChain chain)
            throws IOException, ServletException {
        HttpServletRequest httpRequest = (HttpServletRequest) request;
        HttpServletResponse httpResponse = (HttpServletResponse) response;
        // переход на заданную страницу; дальше по цепочке запрос не передаём,
        // иначе после sendRedirect() будет попытка записать в уже закрытый ответ
        httpResponse.sendRedirect(httpRequest.getContextPath() + indexPath);
    }

    @Override
    public void destroy() { }
}
