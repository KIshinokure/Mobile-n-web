<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<%@ page import="by.bsac.request.RequestTools" %>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Вход</title>
    <link rel="stylesheet" href="${pageContext.request.contextPath}/style.css">
</head>
<body>
<div class="card login">
    <h2>Вход в приложение</h2>
    <form action="${pageContext.request.contextPath}/login" method="POST">
        <div>Войти по:
            <label style="display:inline"><input type="radio" name="authType" value="name" checked> имени</label>
            <label style="display:inline"><input type="radio" name="authType" value="email"> e-mail</label>
        </div>
        <label for="loginValue">Имя пользователя или e-mail</label>
        <input type="text" id="loginValue" name="loginValue" required>
        <label for="psw">Пароль</label>
        <input type="password" id="psw" name="psw" required>
        <button class="btn" type="submit">Войти</button>
    </form>
    <p class="muted">Неверных попыток в этой сессии: ${sessionScope.failedLoginAttemptsCount == null ? 0 : sessionScope.failedLoginAttemptsCount}
        из <%= RequestTools.MAX_FAILED_ATTEMPTS %></p>
</div>
</body>
</html>
