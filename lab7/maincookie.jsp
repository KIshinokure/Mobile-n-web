<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>from Cookie</title>
<link rel="stylesheet" href="${pageContext.request.contextPath}/style.css"></head>
<body>
<main>
<div class="card">
    <h2># 7 # информация из cookie</h2>
    <% java.util.List<?> messages = (java.util.List<?>) request.getAttribute("messages");
       if (messages == null || messages.isEmpty()) { %>
        <p class="muted">Запрос пришёл без cookie.</p>
    <% } else { for (Object m : messages) { %>
        <div><%= by.bsac.variant.OperatorCounter.escape(String.valueOf(m)) %></div>
    <% } } %>
    <p><a href="cookiecontroller">Messages from Cookie</a> · <a href="home.jsp">home.jsp</a></p>
</div>
</main>
</body>
</html>
