package by.bsac.profile;

import java.io.Serializable;

/** Пользователь приложения */
public class Person implements Serializable {

    public enum Role { USER, ADMINISTRATOR }

    private final String name;
    private final String email;
    private final String password;
    private final Role role;
    private String loginDate;

    public Person(String name, String email, String password, Role role) {
        this.name = name;
        this.email = email;
        this.password = password;
        this.role = role;
    }

    public String getName() { return name; }
    public String getEmail() { return email; }
    public String getPassword() { return password; }
    public Role getRole() { return role; }
    public String getLoginDate() { return loginDate; }
    public void setLoginDate(String loginDate) { this.loginDate = loginDate; }
}
