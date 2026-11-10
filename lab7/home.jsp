<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="by.bsac.profile.ProfileTools, by.bsac.profile.Person, java.util.List, java.util.Date, java.text.SimpleDateFormat" %>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Главная</title>
    <link rel="stylesheet" href="${pageContext.request.contextPath}/style.css">
</head>
<body>
<header>
    <h1>ЛР 7 · Сессии, события, фильтры</h1>
    <span>Пользователь: <b>${sessionScope.loggedInUser}</b></span>
    <a href="${pageContext.request.contextPath}/login?action=logout">Выйти</a>
</header>
<main>
    <div class="card">
        <h2>Данные авторизации (хранятся в сессии)</h2>
        <table>
            <tr><th>Логин</th><td>${sessionScope.loggedInUser}</td></tr>
            <tr><th>Способ входа</th><td>${sessionScope.authType}</td></tr>
            <tr><th>Роль</th><td>${sessionScope.role}</td></tr>
            <tr><th>Дата и время входа</th><td>${sessionScope.loginDate}</td></tr>
            <tr><th>Время работы</th><td><%= ProfileTools.workTime(session) %></td></tr>
            <tr><th>ID сессии</th><td class="tag">${pageContext.session.id}</td></tr>
            <tr><th>Создана</th><td><%= new SimpleDateFormat("dd.MM.yyyy HH:mm:ss").format(new Date(session.getCreationTime())) %></td></tr>
            <tr><th>MaxInactiveInterval</th><td>${pageContext.session.maxInactiveInterval} с</td></tr>
        </table>
    </div>

    <div class="card">
        <h2>Задания</h2>
        <ul>
            <li><a href="operators">Вариант 5 — подсчёт операторов программы, результат в cookie</a></li>
            <li><a href="sessionservlet">Атрибуты сессии и счётчик обращений (SessionControlServlet)</a></li>
            <li><a href="timeoutservlet">Инвалидация сессии по времени (TimeoutServlet, 10 с)</a></li>
            <li><a href="cookiecontroller">Создание и чтение cookie (CookieController)</a></li>
            <li><a href="sessionattr.jsp">Прямой доступ к sessionattr.jsp (перехватит PageRedirectSecurityFilter)</a></li>
        </ul>
    </div>

    <% if (Boolean.TRUE.equals(session.getAttribute(ProfileTools.PERSON_IS_ADMIN))) {
           @SuppressWarnings("unchecked")
           List<Person> persons = (List<Person>) session.getAttribute(ProfileTools.ALL_PERSONS_ATTRIBUTE_NAME); %>
    <div class="card">
        <h2>Все пользователи (видно только администратору)</h2>
        <table>
            <tr><th>Имя</th><th>E-mail</th><th>Роль</th><th>Последний вход</th></tr>
            <% for (Person p : persons) { %>
            <tr><td><%= p.getName() %></td><td><%= p.getEmail() %></td><td><%= p.getRole() %></td>
                <td><%= p.getLoginDate() == null ? "—" : p.getLoginDate() %></td></tr>
            <% } %>
        </table>
    </div>
    <% } %>
</main>
</body>
</html>
