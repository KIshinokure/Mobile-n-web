package by.bsac.control.listener;

import jakarta.servlet.ServletContext;
import jakarta.servlet.ServletContextEvent;
import jakarta.servlet.ServletContextListener;
import jakarta.servlet.annotation.WebListener;

/* # 12 # обработка событий инициализации контекста приложения */
@WebListener
public class SimpleContextListener implements ServletContextListener {

    @Override
    public void contextInitialized(ServletContextEvent ev) {
        ServletContext context = ev.getServletContext();
        String mailFeedback = context.getInitParameter("feedback");
        context.log("Context Initialized with parameter: " + mailFeedback);
        System.out.println("Context Initialized with parameter: " + mailFeedback);
    }

    @Override
    public void contextDestroyed(ServletContextEvent ev) {
        System.out.println("Context Destroyed");
    }
}
