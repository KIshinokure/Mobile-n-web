<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Login failed</title>
    <link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css">
</head>
<body>
<div class="card login blocked">
    <h2>Доступ заблокирован</h2>
    <p>You are blocked since you tried to type incorrect login or password 3 times.</p>
    <p>Вы заблокированы: трижды введены неверные логин или пароль.</p>
    <p>Blocked date: <b>${blockedDate}</b></p>
    <p>Blocked time: <b>${blockedTime}</b></p>
    <p class="muted">Блокировка хранится в сессии (атрибуты failedLoginAttemptsCount, blockedDate, blockedTime).</p>
</div>
</body>
</html>
