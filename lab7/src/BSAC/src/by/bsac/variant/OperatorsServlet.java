package by.bsac.variant;

import jakarta.servlet.ServletException;
import jakarta.servlet.annotation.WebServlet;
import jakarta.servlet.http.Cookie;
import jakarta.servlet.http.HttpServlet;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import java.io.IOException;
import java.io.InputStream;
import java.net.URLEncoder;
import java.nio.charset.StandardCharsets;
import java.util.LinkedHashMap;
import java.util.List;
import java.util.Map;
import java.util.Set;
import java.util.TreeSet;

/**
 * Вариант 5. GET /operators?file=program.java
 * Читает файл с кодом программы из WEB-INF/data, считает операторы
 * и записывает результат в cookie: количество и перечень найденных операторов.
 */
@WebServlet("/operators")
public class OperatorsServlet extends HttpServlet {

    private static final String DATA_DIR = "/WEB-INF/data/";

    private Set<String> availableFiles() {
        Set<String> names = new TreeSet<>();
        Set<String> paths = getServletContext().getResourcePaths(DATA_DIR);
        if (paths != null) {
            for (String p : paths) {
                names.add(p.substring(DATA_DIR.length()));
            }
        }
        return names;
    }

    @Override
    protected void doGet(HttpServletRequest req, HttpServletResponse resp)
            throws ServletException, IOException {
        Set<String> files = availableFiles();
        String file = req.getParameter("file");
        if (file == null || !files.contains(file)) {   // только файлы из списка — без обхода каталогов
            file = files.contains("program.java") ? "program.java" : files.stream().findFirst().orElse(null);
        }

        String code = "";
        if (file != null) {
            try (InputStream in = getServletContext().getResourceAsStream(DATA_DIR + file)) {
                code = new String(in.readAllBytes(), StandardCharsets.UTF_8);
            }
        }
        OperatorCounter.Result result = OperatorCounter.count(code);

        // --- запись результатов поиска в cookie ---
        Map<String, String> written = new LinkedHashMap<>();
        written.put("op_file", file);
        written.put("op_total", String.valueOf(result.total));
        written.put("op_list", result.toCookieText());
        for (Map.Entry<String, String> e : written.entrySet()) {
            Cookie c = new Cookie(e.getKey(), URLEncoder.encode(e.getValue(), StandardCharsets.UTF_8));
            c.setMaxAge(24 * 60 * 60);
            c.setPath(req.getContextPath());
            c.setHttpOnly(true);
            resp.addCookie(c);
        }

        // история просмотренных файлов — в сессии
        HttpSession session = req.getSession();
        @SuppressWarnings("unchecked")
        Set<String> viewed = (Set<String>) session.getAttribute("viewedFiles");
        if (viewed == null) {
            viewed = new TreeSet<>();
        }
        viewed.add(file);
        session.setAttribute("viewedFiles", viewed);

        req.setAttribute("files", files);
        req.setAttribute("file", file);
        req.setAttribute("code", code);
        req.setAttribute("result", result);
        req.setAttribute("written", written);
        req.getRequestDispatcher("/jsp/operators.jsp").forward(req, resp);
    }

    /** для JSP: номера строк через запятую */
    public static String joinLines(List<Integer> lines) {
        StringBuilder sb = new StringBuilder();
        for (Integer l : lines) {
            sb.append(sb.length() == 0 ? "" : ", ").append(l);
        }
        return sb.toString();
    }
}
