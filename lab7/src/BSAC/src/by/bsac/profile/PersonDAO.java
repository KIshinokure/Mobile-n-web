package by.bsac.profile;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;
import java.util.concurrent.ConcurrentHashMap;

/** Хранилище пользователей в памяти приложения (вместо БД) */
public class PersonDAO {

    private static final PersonDAO INSTANCE = new PersonDAO();

    private final Map<String, Person> byName = new ConcurrentHashMap<>();

    private PersonDAO() {
        save(new Person("admin", "admin@bsac.by", "admin123", Person.Role.ADMINISTRATOR));
        save(new Person("student", "student@bsac.by", "student5", Person.Role.USER));
        save(new Person("guest", "guest@bsac.by", "guest", Person.Role.USER));
    }

    public static PersonDAO getInstance() { return INSTANCE; }

    public void save(Person person) { byName.put(person.getName(), person); }

    public Person findByName(String name) { return name == null ? null : byName.get(name); }

    public Person findByEmail(String email) {
        if (email == null) return null;
        return byName.values().stream().filter(p -> p.getEmail().equalsIgnoreCase(email)).findFirst().orElse(null);
    }

    public List<Person> getAll() { return new ArrayList<>(byName.values()); }
}
