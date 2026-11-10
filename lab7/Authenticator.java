package by.bsac.profile;

/** Проверка учётных данных. Возвращает пользователя или null, если данные неверны. */
public interface Authenticator {

    Person authenticateByUserName(String name, String password);

    Person authenticateByUserEmail(String email, String password);
}
