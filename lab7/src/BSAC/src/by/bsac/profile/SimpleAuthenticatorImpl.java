package by.bsac.profile;

import java.security.MessageDigest;
import java.nio.charset.StandardCharsets;

/** Аутентификация по имени или e-mail среди пользователей PersonDAO */
public class SimpleAuthenticatorImpl implements Authenticator {

    private final PersonDAO dao = PersonDAO.getInstance();

    @Override
    public Person authenticateByUserName(String name, String password) {
        return check(dao.findByName(name), password);
    }

    @Override
    public Person authenticateByUserEmail(String email, String password) {
        return check(dao.findByEmail(email), password);
    }

    private Person check(Person person, String password) {
        if (person == null || password == null) {
            return null;
        }
        // сравнение за постоянное время, чтобы не подсказывать длину совпадения
        boolean ok = MessageDigest.isEqual(
                person.getPassword().getBytes(StandardCharsets.UTF_8),
                password.getBytes(StandardCharsets.UTF_8));
        return ok ? person : null;
    }
}
