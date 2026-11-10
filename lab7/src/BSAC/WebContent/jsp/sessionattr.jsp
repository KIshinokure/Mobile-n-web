<%@ page language="java" contentType="text/html; charset=UTF-8" pageEncoding="UTF-8"%>
<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Session Attribute</title>
<link rel="stylesheet" href="${pageContext.request.contextPath}/css/style.css"></head>
<body>
<main>
<div class="card">
    <h2># 2 # информация о сессии</h2>
    Role: ${role}
    <br /><hr />
    Counter: ${counter}
    <br /><hr />
    MaxInactiveInterval: ${pageContext.session.maxInactiveInterval}<br/>
    ID session: ${pageContext.session.id}<br/>
    Lifecycle: ${lifecycle}<br/>
    <p><a href="sessionservlet">Обновить (ещё одно обращение к сервлету)</a> ·
       <a href="home.jsp">Back to home.jsp</a></p>
</div>
</main>
</body>
</html>
