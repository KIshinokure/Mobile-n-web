<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="by.bsac.variant.OperatorCounter, by.bsac.variant.OperatorsServlet, java.util.*, java.net.URLDecoder, java.nio.charset.StandardCharsets" %>
<%
    OperatorCounter.Result result = (OperatorCounter.Result) request.getAttribute("result");
    String file = (String) request.getAttribute("file");
    String code = (String) request.getAttribute("code");
    @SuppressWarnings("unchecked") Set<String> files = (Set<String>) request.getAttribute("files");
    @SuppressWarnings("unchecked") Map<String, String> written = (Map<String, String>) request.getAttribute("written");
%>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Вариант 5 — операторы программы</title>
    <link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>
<header>
    <h1>Вариант 5 · Подсчёт операторов программы</h1>
    <span>Пользователь: <b>${sessionScope.loggedInUser}</b> · вход: ${sessionScope.loginDate}</span>
    <a href="home.jsp">Главная</a>
</header>
<main>
    <div class="card">
        <form method="GET" action="operators">
            Файл с кодом программы:
            <select name="file">
                <% for (String f : files) { %>
                <option <%= f.equals(file) ? "selected" : "" %>><%= OperatorCounter.escape(f) %></option>
                <% } %>
            </select>
            <button class="btn" style="margin-top:0" type="submit">Подсчитать</button>
        </form>
    </div>

    <div class="card">
        <h2>Результат: найдено операторов — <%= result.total %></h2>
        <table>
            <tr><th>Оператор</th><th>Количество</th><th>Номера строк</th></tr>
            <% for (Map.Entry<String, Integer> e : result.counts.entrySet()) { %>
            <tr><td class="tag"><%= e.getKey() %></td><td><%= e.getValue() %></td>
                <td><%= OperatorsServlet.joinLines(result.lines.get(e.getKey())) %></td></tr>
            <% } %>
        </table>
    </div>

    <div class="card">
        <h2>Записано в cookie (этим ответом)</h2>
        <table>
            <tr><th>Cookie</th><th>Значение</th></tr>
            <% for (Map.Entry<String, String> e : written.entrySet()) { %>
            <tr><td class="tag"><%= e.getKey() %></td><td><%= OperatorCounter.escape(e.getValue()) %></td></tr>
            <% } %>
        </table>
        <h2 style="margin-top:16px">Пришло от браузера в этом запросе</h2>
        <table>
            <tr><th>Cookie</th><th>Значение</th></tr>
            <% Cookie[] cookies = request.getCookies();
               boolean any = false;
               if (cookies != null) for (Cookie c : cookies) {
                   if (!c.getName().startsWith("op_")) continue; any = true; %>
            <tr><td class="tag"><%= c.getName() %></td>
                <td><%= OperatorCounter.escape(URLDecoder.decode(c.getValue(), StandardCharsets.UTF_8)) %></td></tr>
            <% } if (!any) { %>
            <tr><td colspan="2" class="muted">пока нет — cookie появятся в следующем запросе</td></tr>
            <% } %>
        </table>
        <p class="muted">Просмотренные в этой сессии файлы: ${sessionScope.viewedFiles}</p>
    </div>

    <div class="card">
        <h2>Код программы: <%= OperatorCounter.escape(file) %></h2>
        <pre class="code"><%
            String[] rows = code.split("\n", -1);
            for (int i = 0; i < rows.length; i++) {
                out.print("<span class=\"ln\">" + (i + 1) + "</span>" + OperatorCounter.escape(rows[i]) + "\n");
            } %></pre>
    </div>
</main>
</body>
</html>
