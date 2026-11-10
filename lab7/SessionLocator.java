package by.bsac.timeoutsession;

import jakarta.servlet.http.HttpSession;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Date;

/* # 5 # формирование сообщений для передачи в jsp */
public class SessionLocator {

    private static final String BR = "<br/><hr/>";

    public static ArrayList<String> addMessage(HttpSession session, boolean alive) {
        ArrayList<String> messages = new ArrayList<>();
        SimpleDateFormat f = new SimpleDateFormat("dd.MM.yyyy HH:mm:ss");
        if (alive) { // если сессия существует
            Long start = (Long) session.getAttribute("demoSessionStart");
            messages.add("Creation Time : " + f.format(new Date(start)) + BR);
            messages.add("Session id : " + session.getId() + BR);
            messages.add("Max inactive interval : " + TimeoutServlet.TIME_LIVE_SECONDS + " s" + BR);
            messages.add("Session alive!" + BR);
        } else { // если сессии уже не существует
            messages.add("Session disabled!" + BR);
        }
        return messages;
    }
}
