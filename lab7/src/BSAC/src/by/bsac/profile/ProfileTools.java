package by.bsac.profile;

import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpSession;
import java.time.Duration;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

/** Вспомогательные методы и имена атрибутов сессии, связанные с профилем */
public final class ProfileTools {

    public static final String SESSION_LOGGEDIN_ATTRIBUTE_NAME = "loggedInUser";
    public static final String PERSON_IS_ADMIN = "isAdmin";
    public static final String ALL_PERSONS_ATTRIBUTE_NAME = "allPersons";
    public static final String LOGIN_DATE_ATTRIBUTE_NAME = "loginDate";
    public static final String LOGIN_MILLIS_ATTRIBUTE_NAME = "loginMillis";
    public static final String AUTH_TYPE_ATTRIBUTE_NAME = "authType";

    private static final DateTimeFormatter FORMAT = DateTimeFormatter.ofPattern("dd.MM.yyyy HH:mm:ss");

    private ProfileTools() { }

    public static boolean isLoggedIn(HttpServletRequest req) {
        HttpSession session = req.getSession(false);
        return session != null && session.getAttribute(SESSION_LOGGEDIN_ATTRIBUTE_NAME) != null;
    }

    public static boolean isAdmin(Person person) {
        return person != null && person.getRole() == Person.Role.ADMINISTRATOR;
    }

    public static String generateLoginDate() {
        return LocalDateTime.now().format(FORMAT);
    }

    /** Время работы с приложения с момента входа: ЧЧ:ММ:СС */
    public static String workTime(HttpSession session) {
        Object start = session.getAttribute(LOGIN_MILLIS_ATTRIBUTE_NAME);
        if (!(start instanceof Long)) {
            return "—";
        }
        Duration d = Duration.ofMillis(System.currentTimeMillis() - (Long) start);
        return String.format("%02d:%02d:%02d", d.toHours(), d.toMinutesPart(), d.toSecondsPart());
    }
}
