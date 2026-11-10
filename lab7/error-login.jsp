<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="by.bsac.request.RequestTools" %>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ошибка входа</title>
    <link rel="stylesheet" href="${pageContext.request.contextPath}/style.css">
</head>
<body>
<div class="card login">
    <h2>Ошибка входа</h2>
    <p class="error">Неверный логин или пароль.</p>
    <p>Неудачных попыток: ${sessionScope.failedLoginAttemptsCount}.
       Осталось попыток до блокировки: <b><%= RequestTools.attemptsLeft(session) %></b>.</p>
    <p><a href="${pageContext.request.contextPath}/login">Попробовать ещё раз</a></p>
</div>
</body>
</html>
