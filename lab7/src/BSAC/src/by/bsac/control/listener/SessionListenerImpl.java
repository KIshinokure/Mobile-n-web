package by.bsac.control.listener;

import jakarta.servlet.annotation.WebListener;
import jakarta.servlet.http.HttpSessionAttributeListener;
import jakarta.servlet.http.HttpSessionBindingEvent;

/* # 10 # обработка событий добавления и изменения атрибута сессии */
@WebListener
public class SessionListenerImpl implements HttpSessionAttributeListener {

    @Override
    public void attributeRemoved(HttpSessionBindingEvent ev) {
        System.out.println("remove: " + ev.getClass().getSimpleName() + " : " + ev.getName());
    }

    @Override
    public void attributeAdded(HttpSessionBindingEvent ev) {
        // запись в log-файл или иные действия
        System.out.println("add: " + ev.getClass().getSimpleName() + " : " + ev.getName() + " : " + ev.getValue());
    }

    @Override
    public void attributeReplaced(HttpSessionBindingEvent ev) {
        // ev.getValue() при замене возвращает СТАРОЕ значение атрибута
        System.out.println("replace: " + ev.getClass().getSimpleName() + " : " + ev.getName() + " : " + ev.getValue());
    }
}
