package by.bsac.request;

import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import jakarta.servlet.http.HttpSession;
import java.io.IOException;
import java.time.LocalDate;
import java.time.LocalTime;
import java.time.format.DateTimeFormatter;

/** Вспомогательные методы для механизма блокировки после n неудачных попыток входа */
public class RequestTools {

    /** Сколько неверных попыток разрешено до блокировки */
    public static final int MAX_FAILED_ATTEMPTS = 3;

    public static void addLoginAttemptToSession(HttpServletRequest req, HttpServletResponse resp)
            throws IOException, ServletException {
        HttpSession session = req.getSession();
        Object value = session.getAttribute("failedLoginAttemptsCount");
        int attempts = value == null ? 0 : Integer.parseInt(value.toString());
        session.setAttribute("failedLoginAttemptsCount", attempts + 1);
    }

    /**
     * true, если попыток уже MAX_FAILED_ATTEMPTS или больше.
     * В отличие от варианта в методичке, дата и время блокировки фиксируются
     * именно в момент блокировки, а не при первой проверке.
     */
    public static boolean isBlocked(HttpServletRequest req, HttpServletResponse resp)
            throws IOException, ServletException {
        HttpSession session = req.getSession();
        Object value = session.getAttribute("failedLoginAttemptsCount");
        int attempts = value == null ? 0 : Integer.parseInt(value.toString());
        boolean blocked = attempts >= MAX_FAILED_ATTEMPTS;
        if (blocked && (session.getAttribute("blockedDate") == null || session.getAttribute("blockedTime") == null)) {
            session.setAttribute("blockedDate", LocalDate.now().format(DateTimeFormatter.ofPattern("dd.MM.yyyy")));
            session.setAttribute("blockedTime", LocalTime.now().format(DateTimeFormatter.ofPattern("HH:mm:ss")));
        }
        return blocked;
    }

    public static int attemptsLeft(HttpSession session) {
        Object value = session.getAttribute("failedLoginAttemptsCount");
        int attempts = value == null ? 0 : Integer.parseInt(value.toString());
        return Math.max(0, MAX_FAILED_ATTEMPTS - attempts);
    }

    public static void redirectToBlockedPage(HttpServletRequest req, HttpServletResponse resp)
            throws IOException, ServletException {
        req.getRequestDispatcher("/blocked.jsp").forward(req, resp);
    }
}
